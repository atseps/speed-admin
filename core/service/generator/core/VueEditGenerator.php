<?php

declare(strict_types=1);

namespace core\service\generator\core;


class VueEditGenerator extends BaseGenerator 
{

    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{FORM_VIEW}',
            '{UPPER_CAMEL_NAME}',
            '{FORM_DATA}',
            '{FORM_VALIDATE}',
            '{TABLE_COMMENT}',
            '{PK}',
            '{API_DIR}',
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getFormViewContent(),
            $this->getUpperCamelName(),
            $this->getFormDataContent(),
            $this->getFormValidateContent(),
            $this->tableData['table_comment'],
            $this->getPkContent(),
            $this->geApiDir(),
        ];
        $templatePath = $this->getTemplatePath('vue_form');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }



    /**
     * @notes 获取表单内容
     * @return string
     */
    public function getFormViewContent()
    {
        $content = '';
        foreach ($this->tableColumn as $column) {
            if (!$column['is_insert'] || $column['is_pk']) {
                continue;
            }
            $needReplace = [
                '{COLUMN_COMMENT}',
                '{COLUMN_NAME}',
                '{DICT_CODE}',
            ];
            $waitReplace = [
                $column['comment'],
                $column['name'],
                $column['dict_type'],
            ];
            $templatePath = $this->getTemplatePath('form_item/' . $column['show_type']);
            if (!file_exists($templatePath)) {
                continue;
            }
            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath) . PHP_EOL;
        }

        if (!empty($content)) {
            $content = substr($content, 0, -1);
        }

        $content = $this->setBlankSpace($content, '         ');
        return $content;
    }

    /**
     * @notes 获取API目录
     * @return mixed|void
     */
    public function geApiDir(){

        return  $this->classDir . '/' . $this->getTableName();
    }

    /**
     * @notes 获取表单默认字段内容
     * @return string
     */
    public function getFormDataContent()
    {
        $content = '';
        $isExist = [];
        foreach ($this->tableColumn as $column) {
            if (!$column['is_insert']  || $column['is_pk']) {
                continue;
            }
            if (in_array($column['name'], $isExist)) {
                continue;
            }

            // 复选框类型返回数组
            if ($column['show_type'] == 'checkbox') {
                $content .= $column['name'] . ': ' . "[]," . PHP_EOL;
            } else {
                $content .= $column['name'] . ': ' . "undefined," . PHP_EOL;
            }

            $isExist[] = $column['name'];
        }
        if (!empty($content)) {
            $content = substr($content, 0, -1);
        }
        return $this->setBlankSpace($content, '    ');
    }


    /**
     * @notes 表单验证内容
     * @return false|string
     */
    public function getFormValidateContent()
    {
        $content = '';
        $isExist = [];
        $specDictType = ['input', 'textarea', 'editor'];

        foreach ($this->tableColumn as $column) {
            if (!$column['is_required'] || $column['is_pk']) {
                continue;
            }
            if (in_array($column['name'], $isExist)) {
                continue;
            }

            $validateMsg = in_array($column['show_type'], $specDictType) ? '请输入' : '请选择';
            $validateMsg .= $column['comment'];

            $needReplace = [
                '{COLUMN_NAME}',
                '{VALIDATE_MSG}',
            ];
            $waitReplace = [
                $column['name'],
                $validateMsg,
            ];
            $templatePath = $this->getTemplatePath('other_item/formValidate');
            if (!file_exists($templatePath)) {
                continue;
            }

            $content .= $this->replaceFileData($needReplace, $waitReplace, $templatePath) . ',' . PHP_EOL;

            $isExist[] = $column['name'];
        }
        $content = substr($content, 0, -2);
        return $content;
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return mixed|void
     */
    public function getModuleGenerateDir()
    {
        $dir = dirname(app()->getRootPath()) . '/admin/src/views/' . $this->getTableName() . '/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'vue/src/views/' . $this->getTableName() . '/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     */
    public function getGenerateName()
    {
        return 'form.vue';
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