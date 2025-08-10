<?php

namespace app\service\system;

use core\base\BaseService;
use app\model\system\{GenerateTable, GenerateField};
use think\facade\Db;
use core\exception\FailedException;
use core\service\generator\{GenerateService as Generator};

class GeneratorService extends BaseService
{

    public $fieldModel;

    public function __construct(GenerateTable $model, GenerateField $fieldModel)
    {
        $this->model = $model;
        $this->fieldModel = $fieldModel;
    }


    /**
     * 获取数据库表
     * @return array
     */
    public function getDatabaseTable($params)
    {
        $sql = 'SHOW TABLE STATUS WHERE 1=1 ';
        if (!empty($params['table_name'])) {
            $sql .= "AND name LIKE '%" . $params['table_name'] . "%'";
        }
        if (!empty($params['table_comment'])) {
            $sql .= "AND comment LIKE '%" . $params['table_comment'] . "%'";
        }
        $result =  Db::query($sql);
        $lists = array_map("array_change_key_case", $result);
        $page = request()->param('page/d') ?: 1;
        $pageSize = request()->param('pageSize') ?: 15;
        $offset = max(0, ($page - 1) * $pageSize);
        $data = array_slice($lists, $offset, $pageSize, false);
        return [
            'current_page' => $page,
            'data' => $data,
            'total' => count($lists)
        ];
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $data = $this->model->search()->field('id,table_name,table_comment,create_time,update_time')->order('id desc')->paginate();
        return $data;
    }


    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->model->with(['table_column'])->find($id);
        if (is_null($data)) {
            throw new FailedException('数据不存在或已删除');
        }
        return $data;
    }



    /**
     * 保存
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        $this->startTrans();
        try {
            foreach ($data as $item) {
                // 添加主表基础信息
                $item['template_type'] = 0;
                $item['generate_type'] = 0;
                $item['module_name'] = 'adminapi';
                $item['class_dir'] = $this->getTableName($item['table_name']);
                $item['create_userid'] = request()->uid();
                $item['delete_type'] = 0;
                $item['menu_pid'] = 0;
                $item['menu_type'] = 0;
                $item['menu_name'] = $item['table_comment'];
                $result = GenerateTable::create($item);
                //添加字段表数据
                $this->saveFieldData($item,$result->id);
            }            
            $this->commit(); 
            return $result->id;
        } catch (\Exception $e) {
            $this->rollback(); 
            return false;
        }
        return true;
    }

    /**
     * 获取表名
     * @return array|string|string[]
     */
    public function getTableName($table_name)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        return str_replace($tablePrefix, '', $table_name);
    }


    /**
     * 添加字段表数据
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function saveFieldData($data,$table_id){

         // 获取当前数据表字段信息
         $column = self::getTableColumn($data['table_name']);

         $defaultColumn = ['id', 'create_time', 'update_time'];

         $insertColumn = [];
         foreach ($column as $value) {
             $required = 0;
             if ($value['notnull'] && !$value['primary'] && !in_array($value['name'], $defaultColumn)) {
                 $required = 1;
             }
             $fieldData = [
                 'table_id' => $table_id,
                 'name' => $value['name'],
                 'comment' => $value['comment'],
                 'type' => $this->getDbFieldType($value['type']),
                 'is_required' => $required,
                 'is_pk' => $value['primary'] ? 1 : 0,
             ];
             if (!in_array($value['name'], $defaultColumn)) {
                 $fieldData['is_insert'] = 1;
                 $fieldData['is_list'] = 1;
                 $fieldData['is_search'] = 1;
             }
             $insertColumn[] = $fieldData;
         }
         $this->fieldModel->saveAll($insertColumn);
    }


    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $this->startTrans();
        try {
            $this->model->updateBy($id, $data);
            $this->fieldModel->saveAll($data['column']);
            $this->commit(); 
        } catch (\Exception $e) {
            $this->rollback(); 
            return false;
        }
        return true;
    }


    /**
     * 删除
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function delete($id)
    {
        //开启事务
        $this->startTrans();
        try {
            GenerateTable::destroy($id);
            $this->fieldModel->where('table_id',$id)->delete();
            $this->commit();  
        } catch (\Exception $e) {
            $this->rollback(); 
            return false;
        }
        return true;
    }

    
    /**
     * 删除字段
     * @param int $id
     * @param array $data
     * @return int
     */
    public function deleteFiled($id)
    {
        return $this->fieldModel->where('id',$id)->delete();
    }




    /**
     * 获取表字段信息
     * @param $tableName
     * @return array
     */
    public static function getTableColumn($tableName)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        $tableName = str_replace($tablePrefix, '', $tableName);
        return Db::name($tableName)->getFields();
    }



    /**
     * 生成代码
     * @param $tableName
     * @return array
     */
    public function makeCode($id)
    {
        $tableData = $this->model->with(['table_column'])->find($id)->toArray();
        $generator = app()->make(Generator::class);
        //删除之前生成的代码
        $generator->delGenerateDirContent();
        //生成代码
        $generator->generator($tableData);

        $zipFile = '';
        
        // 生成压缩包
        if ($tableData['generate_type'] == 0) {
            $generator->zipFile();
            $zipFile = $generator->getDownloadUrl();
        }

        return ['file' => $zipFile];
    }

    /**
     * 预览
     * @param $params
     * @return bool|array
     */
    public  function preview($id)
    {
        try {
            // 获取数据表信息
            $table = $this->model->with(['table_column'])->whereIn('id', $id)->findOrEmpty()->toArray();
            $generator = app()->make(Generator::class);
            return $generator->preview($table);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * 下载文件
     * @param $fileName
     * @return bool|string
     */
    public function download(string $fileName)
    {
        $cacheFileName = cache('curd_file_name' . $fileName);
        if (empty($cacheFileName)) {
            $this->error = '请重新生成代码';
            return false;
        }
        $path = root_path() . 'runtime/generate/' . $fileName;
        if (!file_exists($path)) {
            $this->error = '下载失败';
            return false;
        }
        cache('curd_file_name' . $fileName, null);

        return $path;
    }


    /**
     * 获取数据表字段类型
     * @param string $type
     * @return string
     */
    public function getDbFieldType(string $type): string
    {
        if (0 === strpos($type, 'set') || 0 === strpos($type, 'enum')) {
            $result = 'string';
        } elseif (preg_match('/(double|float|decimal|real|numeric)/is', $type)) {
            $result = 'float';
        } elseif (preg_match('/(int|serial|bit)/is', $type)) {
            $result = 'int';
        } elseif (preg_match('/bool/is', $type)) {
            $result = 'bool';
        } elseif (0 === strpos($type, 'timestamp')) {
            $result = 'timestamp';
        } elseif (0 === strpos($type, 'datetime')) {
            $result = 'datetime';
        } elseif (0 === strpos($type, 'date')) {
            $result = 'date';
        } else {
            $result = 'string';
        }
        return $result;
    }


}
