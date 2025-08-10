<?php

declare(strict_types=1);

namespace core\service\generator\core;

class ControllerGenerator extends BaseGenerator 
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
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getUseContent(),
            $this->getUpperCamelName(),
            $this->getExtendsControllerContent(),
        ];

        $templatePath = $this->getTemplatePath('controller');
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
            return "namespace app\\" . $this->moduleName . "\\controller\\" . $this->classDir . ';';
        }
        return "namespace app\\" . $this->moduleName . "\\controller;";
    }


    /**
     * @notes 获取use模板内容
     * @return string
     */
    public function getUseContent()
    {

        if ($this->moduleName == 'adminapi') {
            $tpl = "use core\base\BaseController;" . PHP_EOL;
        } else {
            $tpl = "use core\base\BaseController;" . PHP_EOL;
        }
        if (!empty($this->classDir)) {
            $tpl .= 
                "use app\service\\" . $this->classDir . "\\" . $this->getUpperCamelName() . "Service;" . PHP_EOL .
                "use app\\" . $this->moduleName . "\\validate\\" . $this->classDir . "\\" . $this->getUpperCamelName() . "Validate;";
        }
        return $tpl;
    }


 

    /**
     * @notes 获取继承控制器
     * @return string
     */
    public function getExtendsControllerContent()
    {
        $tpl = 'BaseController';
        if ($this->moduleName != 'adminapi') {
            $tpl = 'BaseController';
        }
        return $tpl;
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/controller/';
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
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/controller/';
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
        return $this->getUpperCamelName() . '.php';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' =>  $this->getUpperCamelName() . 'Controller.php',
            'type' => 'php',
            'content' => $this->content
        ];
    }

}