<?php


declare(strict_types=1);

namespace core\service\generator\core;
use think\helper\Str;


class VueIndexGenerator extends BaseGenerator  
{

    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{UPPER_CAMEL_NAME}',
            '{NAME}',
            '{COLUMNS}',
            '{SEARCHCOL}',
            '{API_DIR}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getUpperCamelName(),
            $this->getTableName(),
            $this->geTableColumns(),
            $this->geSearchColumns(),
            $this->geApiDir(),
        ];
        $templatePath = $this->getTemplatePath('vue_index');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath) ;

        $this->setContent($content);

    }



    /**
     * @notes 获取表格列
     * @return mixed|void
     */
    public function geTableColumns(){
        $content = '';
        foreach ($this->tableColumn as $column) {
            if (!$column['is_list'] ) {
                continue;
            }
            $needReplace = [
                '{TITLE}', 
                '{FIELD}',     
            ];
            $waitReplace = [
                $column['comment'],
                $column['name'],        
            ];
            $templatePath = $this->getTemplatePath('other_item/tableColumn');
            if (!file_exists($templatePath)) {
                continue;
            }
            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath) . PHP_EOL;
        }
        if (!empty($content)) {
            $content = substr($content, 0, -1);
        }
        return $this->setBlankSpace($content, "    ");
    }


    /**
     * @notes 获取搜索表单列
     * @return mixed|void
     */
    public function geSearchColumns(){
        $content = '';
       
        foreach ($this->tableColumn as $column) {
            if (!$column['is_search'] ) {
                continue;
            }
            $needReplace = [
                '{TITLE}', 
                '{FIELD}', 
                '{COMPONENT}'    
            ];
            $waitReplace = [
                $column['comment'],
                $column['name'],
                Str::studly($column['show_type'])        
            ];
            $templatePath = $this->getTemplatePath('other_item/searchColumn');
            if (!file_exists($templatePath)) {
                continue;
            }
            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath). PHP_EOL;
        }
        if (!empty($content)) {
            $content = substr($content, 0, -2);
        }
        return $this->setBlankSpace($content, "    ");
    }

    /**
     * @notes 获取API目录
     * @return mixed|void
     */
    public function geApiDir(){

        return  $this->classDir . '/' . $this->getTableName();
    }

    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return mixed|void
     */
    public function getModuleGenerateDir()
    {
        $dir = dirname(app()->getRootPath()) . '/admin/src/views/' . $this->getLowerTableName() . '/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'vue/src/views/' . $this->getLowerTableName() . '/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     */
    public function getGenerateName()
    {
        return 'index.vue';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getGenerateName(),
            'type' => 'vue',
            'content' => $this->content
        ];
    }


}