<?php

namespace core\service\generator;

use core\service\generator\core\{
    ControllerGenerator,
    ModelGenerator,
    ServiceGenerator,
    ValidateGenerator,
    SqlGenerator,
    RouteGenerator,
    VueApiGenerator,
    VueIndexGenerator,
    VueEditGenerator
};

class GenerateService
{


    // 生成文件路径
    protected $generatePath;

    // runtime目录
    protected $runtimePath;

    // 压缩包名称
    protected $zipTempName;

    // 压缩包临时路径
    protected $zipTempPath;
    public function __construct()
    {
        $this->generatePath = root_path() . 'runtime/generate/';
        $this->runtimePath = root_path() . 'runtime/';
    }

    /**
     * @notes 生成器相关类
     * @return string[]
     */
    public function getGeneratorClass()
    {
        return [
            ControllerGenerator::class,
            ModelGenerator::class,
            ServiceGenerator::class,
            ValidateGenerator::class,
            RouteGenerator::class,
            SqlGenerator::class,
            VueApiGenerator::class,
            VueIndexGenerator::class,
            VueEditGenerator::class
        ];
    }

    /**
     * @notes 删除生成文件夹内容
     */
    public function delGenerateDirContent()
    {
        // 删除runtime目录制定文件夹
        !is_dir($this->generatePath) && mkdir($this->generatePath, 0755, true);
        del_target_dir($this->generatePath, true);
    }

    public function generator($tableData)
    {
        foreach ($this->getGeneratorClass() as $item) {
            $generator = app()->make($item);
            $generator->initGenerateData($tableData)->generate();
            // 是否构建菜单
            if ($item == 'core\service\generator\core\SqlGenerator') {
                $generator->isBuildMenu() && $generator->buildMenuHandle();
            }
        }
    }

    /**
     * @notes 预览文件
     * @param array $tableData
     * @return array
     */
    public function preview(array $tableData)
    {
        $data = [];
        foreach ($this->getGeneratorClass() as $item) {
            $generator = app()->make($item);
            $generator->initGenerateData($tableData);
            $data[] = $generator->fileInfo();
        }
        return $data;
    }

    
    /**
     * @notes 压缩文件
     */
    public function zipFile()
    {
        $fileName = 'curd-' . date('YmdHis') . '.zip';
        $this->zipTempName = $fileName;
        $this->zipTempPath = $this->generatePath . $fileName;
        $zip = new \ZipArchive();
        $zip->open($this->zipTempPath, \ZipArchive::CREATE);
        $this->addFileZip($this->runtimePath, 'generate', $zip);
        $zip->close();
    }


    /**
     * @notes 往压缩包写入文件
     * @param $basePath
     * @param $dirName
     * @param $zip
     */
    public function addFileZip($basePath, $dirName, $zip)
    {
        $handler = opendir($basePath . $dirName);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                if (is_dir($basePath . $dirName . '/' . $filename)) {
                    // 当前路径是文件夹
                    $this->addFileZip($basePath, $dirName . '/' . $filename, $zip);
                } else {
                    // 写入文件到压缩包
                    $zip->addFile($basePath . $dirName . '/' . $filename, $dirName . '/' . $filename);
                }
            }
        }
        closedir($handler);
    }


    /**
     * @notes 返回压缩包临时路径
     * @return mixed
     */
    public function getDownloadUrl()
    {
        $vars = ['file' => $this->zipTempName];
        cache('curd_file_name' . $this->zipTempName, $this->zipTempName, 3600);
        return (string)url("adminapi/generator/download", $vars, false, true);
    }





}
