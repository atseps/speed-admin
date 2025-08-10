<?php

declare(strict_types=1);

namespace core\service\generator\core;

use think\facade\Db;

class SqlGenerator extends BaseGenerator 
{

    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{MENU_TABLE}',
            '{PID}',
            '{MENU_NAME}',
            '{PATH_NAME}',
            '{COMPONENT_NAME}',
            '{RULES}',
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getMenuTableNameContent(),
            $this->getMenuPidContent(),
            $this->getMenuNameContent(),
            $this->getLowerTableName(),
            $this->getComponentName(),
            $this->getRulesName(),
        ];

        $templatePath = $this->getTemplatePath('sql');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    /**
     * @notes 菜单名称
     * @return mixed
     */
    public function getMenuNameContent()
    {
        return $this->tableData['menu_name'] ?? $this->tableData['table_comment'];
    }



    /**
     * @notes 组件地址
     * @return mixed
     */
    public function getComponentName(){
        return $this->tableData['class_dir'] . '/' .  $this->getLowerTableName() . '/index';
    }


    /**
     * @notes 权限节点
     * @return mixed
     */
    public function getRulesName(){
   
        return $this->tableData['class_dir'] . ':' .  $this->getLowerTableName();
        
    }



    /**
     * @notes 获取上级菜单内容
     * @return int|mixed
     */
    public function getMenuPidContent()
    {
        return $this->tableData['menu_pid'] ?? 0;
    }


    /**
     * @notes 获取菜单表内容
     * @return string
     */
    public function getMenuTableNameContent()
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        return $tablePrefix . 'menu';
    }


    /**
     * @notes 是否构建菜单
     * @return bool
     */
    public function isBuildMenu()
    {
        $menuType = $this->tableData['menu_type'] ?? 0;
        return $menuType == 1;
    }


    /**
     * @notes 构建菜单
     * @return bool
     */
    public function buildMenuHandle()
    {
        if (empty($this->content)) {
            return false;
        }
        $sqls = explode(';', trim($this->content));
        //执行sql
        foreach ($sqls as $sql) {
            if (!empty(trim($sql))) {
                Db::execute($sql . ';');
            }
        }
        return true;
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return mixed|void
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->generatorDir . 'sql/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'sql/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     */
    public function getGenerateName()
    {
        return 'menu.sql';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getGenerateName(),
            'type' => 'sql',
            'content' => $this->content
        ];
    }


}