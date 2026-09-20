<?php

declare(strict_types=1);

namespace core\service\generator\core;

class ServiceGenerator extends BaseGenerator 
{

    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',  //命名空间
            '{USE}',         //引入类
            '{UPPER_CAMEL_NAME}', //类名称
            '{EXTENDS_CONTROLLER}', //父类名
            '{APPEND_CONTENT}', //列表追加的获取器字段
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getUseContent(),
            $this->getUpperCamelName(),
            $this->getExtendsServiceContent(),
            $this->getAppendContent(),
        ];

        $templatePath = $this->getTemplatePath('service');
        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    /**
     * @notes 获取命名空间内容
     * @return string
     */
    public function getNameSpaceContent()
    {
        if (!empty($this->classDir)) {
            return "namespace app\\service\\" . $this->classDir . ';';
        }
        return "namespace app\service;";
    }


    /**
     * @notes 获取use模板内容
     * @return string
     */
    public function getUseContent()
    {

        if ($this->moduleName == 'adminapi') {
            $tpl = "use core\base\BaseService;" . PHP_EOL;
        } else {
            $tpl = "use core\base\BaseService;" . PHP_EOL;
        }
        if (!empty($this->classDir)) {
            $tpl .= "use app\model\\" . $this->classDir . "\\" . $this->getUpperCamelName() . ";";    
        }
        return $tpl;
    }


 

    /**
     * @notes 获取继承控制器
     * @return string
     */
    public function getExtendsServiceContent()
    {
        $tpl = 'BaseService';
        if ($this->moduleName != 'adminapi') {
            $tpl = 'BaseService';
        }
        return $tpl;
    }


    /**
     * @notes 获取列表需要追加的获取器字段内容
     * 只要字段配置了字典类型就追加，追加后查询结果会多出 {字段}_text 文本字段，供列表页直接展示字典文本
     * @return string
     */
    public function getAppendContent()
    {
        $appendFields = [];
        foreach ($this->tableColumn as $column) {
            if (empty($column['dict_type'])) {
                continue;
            }
            $appendFields[] = $column['name'] . '_text';
        }

        // 没有字典字段时返回空字符串，保持与原先 getList 完全一致的输出
        if (empty($appendFields)) {
            return '';
        }

        $fields = '';
        foreach ($appendFields as $field) {
            $fields .= "'" . $field . "', ";
        }
        $fields = rtrim($fields, ', ');

        return '->append([' . $fields . '])';
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath  . 'service/';
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'php/app/service/';
        $this->checkDir($dir);
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 生成文件名
     * @return string
     */
    public function getGenerateName()
    {
        return $this->getUpperCamelName() . 'Service.php';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getGenerateName(),
            'type' => 'php',
            'content' => $this->content
        ];
    }


    /**
     * @notes 文件说明信息
     * @return array
     */
    public function getFileDescription(): array
    {
        return ['group' => 'PHP 后端', 'description' => '服务层'];
    }

}