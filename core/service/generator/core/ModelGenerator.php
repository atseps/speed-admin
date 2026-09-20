<?php

declare(strict_types=1);

namespace core\service\generator\core;
use think\helper\Str;

class ModelGenerator extends BaseGenerator 
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
            '{USE}',
            '{SOFTDELETE_CONTENT}',
            '{TABLE_NAME}',
            '{SEARCH_ARRT}',
            '{DICT_TEXT}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getNameSpaceContent(),
            $this->getUpperCamelName(),
            $this->getUseContent(),
            $this->getSoftDeleteContent(),
            $this->getTableName(),
            $this->getSearchArrtContent(),
            $this->getDictTextContent()
        ];

        $templatePath = $this->getTemplatePath('model');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    /**
     * @notes 获取软删除命名空间
     * @return string
     */
    public function getUseContent()
    {
        $tpl = "use core\base\BaseModel;";
        if ($this->tableData['delete_type'] == 1) {
            $tpl = $tpl . PHP_EOL ."use think\model\concern\SoftDelete;";
        }
        return $tpl;
    }

    /**
     * @notes 使用软删除
     * @return string
     */
    public function getSoftDeleteContent()
    {
        if ($this->tableData['delete_type'] == 1) {
            $content = 
                'use SoftDelete;' . PHP_EOL .
                'protected $deleteTime = "delete_time";' . PHP_EOL .
                'protected $defaultSoftDelete = 0;';
            return $this->setBlankSpace($content, "    ");
        }
        return "";
    }

    
    /**
     * @notes 获取命名空间模板内容
     * @return string
     */
    public function getNameSpaceContent()
    {
        if (!empty($this->classDir)) {
            return "namespace app\\model\\" . $this->classDir . ';';
        }
        return "namespace app\\model;";
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . 'model/';
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
        $dir = $this->generatorDir . 'php/app/model/';
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
        return $this->getUpperCamelName() . '.php';
    }

    /**
     * @notes 获取搜索器内容
     * @return string
     */
    public function getSearchArrtContent()
    {
        $content = '';
        foreach ($this->tableColumn as $column) {
            if (!$column['is_search'] ) {
                continue;
            }
            $needReplace = [
                '{UPPER_CAMEL_NAME}', 
                '{VALUE}',     
                '{FIELD}', 
                '{SEARCH_TYPE}',
            ];
            $waitReplace = [
                Str::studly($column['name']),
                $this->getValueContext($column),
                $column['name'],
                $column['search_type'],
            ];
            $templatePath = $this->getTemplatePath('search');
            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath) . PHP_EOL;
        }

        if (!empty($content)) {
            $content = substr($content, 0, -1);
        }
        return $content;
    }


    public function getValueContext($column)
    {
        $content = '$value';
        if($column['search_type'] == 'between time'){
           $content = 'between_time($value)';
        }
        return $content;
    }


    /**
     * @notes 获取字典文本获取器内容
     * 只要字段配置了字典类型就生成，不限制组件类型
     * 生成的获取器不参与编辑写库，仅供列表页直接把字典值转化成文本展示
     * @return string
     */
    public function getDictTextContent()
    {
        $content = '';
        foreach ($this->tableColumn as $column) {
            if (empty($column['dict_type'])) {
                continue;
            }
            $needReplace = [
                '{UPPER_CAMEL_NAME}',
                '{FIELD}',
                '{DICT_CODE}',
                '{COLUMN_COMMENT}',
            ];
            $waitReplace = [
                Str::studly($column['name']),
                $column['name'],
                $column['dict_type'],
                $column['comment'],
            ];
            $templatePath = $this->getTemplatePath('dict_text');
            if (!file_exists($templatePath)) {
                continue;
            }
            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath) . PHP_EOL;
        }

        if (!empty($content)) {
            $content = substr($content, 0, -1);
        }
        return $content;
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getUpperCamelName() . 'Model.php',
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
        return ['group' => 'PHP 后端', 'description' => '模型'];
    }


}