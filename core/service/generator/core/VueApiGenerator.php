<?php

declare(strict_types=1);

namespace core\service\generator\core;
use think\helper\Str;


class VueApiGenerator extends BaseGenerator
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
            '{ROUTE}'
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getUpperCamelName(),
            $this->getRouteContent(),
        ];

        $templatePath = $this->getTemplatePath('vue_api');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


  
    /**
     * @notes 路由名称
     * @return array|string|string[]
     */
    public function getRouteContent()
    {
        $content = $this->getTableName();
        return Str::lower($content);
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return mixed|void
     */
    public function getModuleGenerateDir()
    {
        $dir = dirname(app()->getRootPath()) . '/admin/src/api/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 获取文件生成到runtime的文件夹路径
     * @return string
     */
    public function getRuntimeGenerateDir()
    {
        $dir = $this->generatorDir . 'vue/src/api/';
        $this->checkDir($dir);
        return $dir;
    }


    /**
     * @notes 生成的文件名
     * @return string
     */
    public function getGenerateName()
    {
        return $this->getLowerTableName() . '.ts';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getGenerateName(),
            'type' => 'ts',
            'content' => $this->content
        ];
    }


}