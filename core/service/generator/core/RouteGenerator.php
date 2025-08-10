<?php

declare(strict_types=1);

namespace core\service\generator\core;

class RouteGenerator extends BaseGenerator 
{

    /**
     * @notes 替换变量
     * @return mixed|void
     */
    public function replaceVariables()
    {
        // 需要替换的变量
        $needReplace = [
            '{ROUTE}',
            '{ROUTE_RULES}',
        ];

        // 等待替换的内容
        $waitReplace = [
            $this->getTableName(),
            $this->getRouteRulesName()
        ];

        $templatePath = $this->getTemplatePath('route');

        // 替换内容
        $content = $this->replaceFileData($needReplace, $waitReplace, $templatePath);

        $this->setContent($content);
    }


    
    /**
     * @notes 获取路由规则
     * @return string
     */
    public function getRouteRulesName()
    {
        return $this->classDir . '.' . $this->getTableName();
    }

    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return string
     */
    public function getModuleGenerateDir()
    {
        $dir = $this->basePath . $this->moduleName . '/route/';
        if (!empty($this->classDir)) {
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
        $dir = $this->generatorDir . 'php/app/' . $this->moduleName . '/route/';
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
        return $this->getTableName() . '.php';
    }


    /**
     * @notes 文件信息
     * @return array
     */
    public function fileInfo(): array
    {
        return [
            'name' => $this->getUpperCamelName() . 'Route.php',
            'type' => 'php',
            'content' => $this->content
        ];
    }


}