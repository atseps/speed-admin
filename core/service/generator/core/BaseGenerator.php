<?php


declare(strict_types=1);

namespace core\service\generator\core;
use core\exception\FailedException;
use think\helper\Str;


/**
 * 生成器基类
 * Class BaseGenerator
 * @package app\common\service\generator\core
 */
abstract class BaseGenerator
{

    /**
     * 模板文件夹
     * @var string
     */
    protected $templateDir;


    /**
     * 模块名
     * @var string
     */
    protected $moduleName;


    /**
     * 类目录
     * @var string
     */
    protected $classDir;


    /**
     * 表信息
     * @var array
     */
    protected $tableData;


    /**
     * 表字段信息
     * @var array
     */
    protected $tableColumn;


    /**
     * 文件内容
     * @var string
     */
    protected $content;


    /**
     * basePath
     * @var string
     */
    protected $basePath;


    /**
     * rootPath
     * @var string
     */
    protected $rootPath;


    /**
     * 生成的文件夹
     * @var string
     */
    protected $generatorDir;


    /**
     * 是否仅预览模式
     * 为 true 时 checkDir() 只校验路径不创建目录，
     * 供「生成目录预览」使用，避免预览时在工程里留下空目录
     * @var bool
     */
    protected $previewOnly = false;


    public function __construct()
    {
        $this->basePath = base_path();
        $this->rootPath = root_path();
        $this->templateDir = $this->rootPath . 'core/service/generator/stub/';
        $this->generatorDir = $this->rootPath . 'runtime/generate/';
        $this->checkDir($this->generatorDir);
    }


    /**
     * @notes 设置为仅预览模式：后续 checkDir() 不再创建目录
     * @param bool $previewOnly
     * @return $this
     */
    public function setPreviewOnly(bool $previewOnly = true)
    {
        $this->previewOnly = $previewOnly;
        return $this;
    }


    /**
     * @notes 文件夹不存在则创建
     * @param string $path
     */
    public function checkDir(string $path)
    {
        $this->assertSafePath($path);
        // 仅预览模式下只做安全校验，不落盘创建目录
        if ($this->previewOnly) {
            return;
        }
        !is_dir($path) && mkdir($path, 0755, true);
    }


    /**
     * @notes 初始化表表数据
     * @param array $tableData
     */
    public function initGenerateData(array $tableData)
    {
        // 设置当前表信息
        $this->setTableData($tableData);
        // 设置模块名
        $this->setModuleName($tableData['module_name']);
        // 设置类目录
        $this->setClassDir($tableData['class_dir'] ?? '');
        // 替换模板变量
        $this->replaceVariables();

        return $this;
    }

    /**
     * 校验文件路径是否安全
     *
     *
     * @param string $path
     * @return void
     */
    protected function assertSafePath(string $path): void
    {
        $target = realpath($path);
        $target = $target !== false ? $target : $this->normalizePath($path);
        $target = rtrim(str_replace('\\', '/', $target), '/') . '/';

        foreach ($this->safeRoots() as $root) {
            $real = realpath(rtrim($root, '/\\'));
            $real = $real !== false ? $real : $this->normalizePath($root);
            $real = rtrim(str_replace('\\', '/', $real), '/') . '/';
            if (strpos($target, $real) === 0) {
                return;   // 命中允许的根目录
            }
        }

        throw new FailedException('非法的代码生成路径，已阻止写入：' . $target);
    }


    /**
     * 允许生成文件写入的根目录
     *
     * @return array
     */
    protected function safeRoots(): array
    {
        return [
            $this->basePath,           // 生成到模块：app/
            $this->generatorDir,       // 生成到runtime：runtime/generate/
            $this->rootPath . 'web/',  // 前端代码：web/
        ];
    }


    /**
     * 规范化路径
     *
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        $path   = str_replace('\\', '/', $path);
        $prefix = str_starts_with($path, '/') ? '/' : '';

        $parts = [];
        foreach (explode('/', $path) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                array_pop($parts);
                continue;
            }
            $parts[] = $part;
        }

        return $prefix . implode('/', $parts);
    }

    /**
     * @notes 生成文件到模块或runtime目录
     */
    public function generate()
    {
        //生成方式  0-压缩包下载 1-生成到模块
        if ($this->tableData['generate_type']) {
            // 生成路径
            $path = $this->getModuleGenerateDir() . $this->getGenerateName();
        } else {
            // 生成到runtime目录
            $path = $this->getRuntimeGenerateDir() . $this->getGenerateName();
        }
        $this->assertSafePath($path);        
        // 写入内容
        file_put_contents($path, $this->content);
    }
    

    /**
     * @notes 设置表信息
     * @param array $tableData
     */
    public function setTableData(array $tableData)
    {
        $this->tableData = !empty($tableData) ? $tableData : [];
        $this->tableColumn = $tableData['table_column'] ?? [];
        $tableName = (string) ($this->tableData['table_name'] ?? '');
        if ($tableName !== '' && !preg_match('/^[A-Za-z0-9_]+$/', $tableName)) {
            throw new FailedException('数据表名称只能由字母、数字、下划线组成');
        }
    }


    /**
     * @notes 设置模块名
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName): void
    {
        // 只允许字母、数字、下划线
        if (!preg_match('/^[A-Za-z0-9_]+$/', $moduleName)) {
            throw new FailedException('模块名只能由字母、数字、下划线组成');
        }
        $this->moduleName = strtolower($moduleName);
    }



    
    /**
     * @notes 设置类目录
     * @param string $classDir
     */
    public function setClassDir(string $classDir): void
    {
        // 只允许字母、数字、下划线及斜杠（禁掉 ; $ ( ) 换行 .. 等一切可注入字符）
        if ($classDir !== '' && !preg_match('/^[A-Za-z0-9_]+(?:\/[A-Za-z0-9_]+)*$/', $classDir)) {
            throw new FailedException('类目录只能由字母、数字、下划线及斜杠组成');
        }
        $this->classDir = $classDir;
    }


    /**
     * @notes 设置生成文件内容
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * @notes 获取模板路径
     * @param string $templateName
     * @return string
     */
    public function getTemplatePath(string $templateName): string
    {
        return $this->templateDir . $templateName . '.stub';
    }

   /**
     * @notes 小驼峰命名
     * @return string
     */
    public function getLowerCamelName()
    {
        return Str::camel($this->getTableName());
    }


    /**
     * @notes 大驼峰命名
     * @return string
     */
    public function getUpperCamelName()
    {
        return Str::studly($this->getTableName());
    }


    /**
     * @notes 表名小写
     * @return string
     */
    public function getLowerTableName()
    {
        return Str::lower($this->getTableName());
    }


    /**
     * @notes 获取表名
     * @return array|string|string[]
     */
    public function getTableName()
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        return str_replace($tablePrefix, '', $this->tableData['table_name']);
    }

    
    /**
     * @notes 获取作者信息
     * @return mixed|string
     */
    public function getAuthorContent()
    {
        return empty($this->tableData['author']) ? 'speedadmin' : $this->tableData['author'];
    }

    /**
     * @notes 代码生成备注时间
     * @return false|string
     */
    public function getNoteDateContent()
    {
        return date('Y/m/d H:i');
    }


    /**
     * @notes 获取文件生成到模块的文件夹路径
     * @return mixed
     */
    abstract public function getModuleGenerateDir();


    /**
     * @notes  获取文件生成到runtime的文件夹路径
     * @return mixed
     */
    abstract public function getRuntimeGenerateDir();


    /**
     * @notes 替换模板变量
     * @return mixed
     */
    abstract public function replaceVariables();


    /**
     * @notes 生成文件名
     * @return mixed
     */
    abstract public function getGenerateName();

    /**
     * @notes 替换内容
     * @param array|string $needReplace
     * @param array|string $waitReplace
     * @param string $template
     * @return array|false|string|string[]
     */
    public function replaceFileData($needReplace, $waitReplace, $template)
    {
        return str_replace($needReplace, $waitReplace, file_get_contents($template));
    }


    /**
     * @notes 获取文件的完整生成路径（不创建目录、不写入文件）
     * @return string 统一使用 / 分隔的展示路径
     */
    public function getGenerateFilePath(): string
    {
        return $this->toRelativePath($this->getModuleGenerateDir() . $this->getGenerateName());
    }


    /**
     * @notes 把绝对路径转换为相对于项目根目录的路径，便于前端展示
     * @param string $path
     * @return string
     */
    protected function toRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $root = str_replace('\\', '/', $this->rootPath);
        // 路径以项目根目录开头时，截掉根目录，只保留相对部分
        if (str_starts_with($path, $root)) {
            $path = substr($path, strlen($root));
        }
        return ltrim($path, '/');
    }


    /**
     * @notes 文件说明信息（分组名 + 文件用途），供生成目录预览使用
     * @return array
     */
    public function getFileDescription(): array
    {
        return ['group' => '其他', 'description' => ''];
    }


    /**
     * @notes 生成方式是否为压缩包
     * @return bool
     */
    public function isGenerateTypeZip()
    {
        return $this->tableData['generate_type'] == 0;
    }


    /**
     * @notes 获取表主键
     * @return mixed|string
     */
    public function getPkContent()
    {
        $pk = 'id';
        if (empty($this->tableColumn)) {
            return $pk;
        }

        foreach ($this->tableColumn as $item) {
            if ($item['is_pk']) {
                $pk = $item['name'];
            }
        }
        return $pk;
    }
    
    

    /**
     * @notes 设置空额占位符
     * @param string $content
     * @param string $blankpace
     * @return string
     */
    public function setBlankSpace(string $content, string $blankpace)
    {
        if(!is_string($content)){
            $content = '';
        }
        $content = explode(PHP_EOL, $content);
        foreach ($content as $line => $text) {
            $content[$line] = $blankpace . $text;
        }
        return (implode(PHP_EOL, $content));
    }


}