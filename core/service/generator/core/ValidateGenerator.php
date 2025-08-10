<?php

declare(strict_types=1);

namespace core\service\generator\core;


class ValidateGenerator extends BaseGenerator 
{


    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{NAMESPACE}',
            '{UPPER_CAMEL_NAME}',
            '{PK}',
            '{RULE}',
            '{FILED_NAME}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getUpperCamelName(),
            $this->getPkContent(),
            $this->getRuleContent(),
            $this->getFieldNameContent(),
        ];

        $templatePath = $this->getTemplatePath('validate');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    /**
     * @notes 验证规则
     * @return mixed|string
     */
    public function getRuleContent()
    {
        $content = "";
        foreach ($this->tableColumn as $column) {
            if ($column['is_required'] == 1) {
                $content .= "'" . $column['name'] . "' => 'require'," . PHP_EOL;
            }
        }
        $content = substr($content, 0, -2);
        return $this->setBlankSpace($content, "        ");
    }


    /**
     * @notes 验证规则字段名称
     * @return mixed|string
     */
    public function getFieldNameContent()
    {
        $content = "";
        foreach ($this->tableColumn as $column) {
            if($column['comment'] && $column['is_required'] == 1){
                $content .= "'" . $column['name'] . "' => ". "'" . $column['comment'] . "'," . PHP_EOL;
            }
        }
        $content = substr($content, 0, -2);
        return $this->setBlankSpace($content, "        ");
    }
    


    /**
     * @notes 获取命名空间模板内容
     * @return string
     */
    public function getNameSpaceContent()
    {
        if (!empty($this->classDir)) {
            return "namespace app\\" . $this->moduleName . "\\validate\\" . $this->classDir . ';';
        }
        return "namespace app\\" . $this->moduleName . "\\validate;";
    }



    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/validate/';
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
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/validate/';
        $this->checkDir($dir);
        if (!empty($this->classDir)) {
            $dir .= $this->classDir . '/';
            $this->checkDir($dir);
        }
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     */
    public function getGenerateName()
    {
        return $this->getUpperCamelName() . 'Validate.php';
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


}