<?php

namespace core\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\Config;

class InstallDatabase extends Command
{
    protected function configure()
    {
        $this->setName('install:database')
            ->setDescription('自动安装数据库');
    }

    protected function execute(Input $input, Output $output)
    {
        // 环境检查
        $this->checkEnvironment($output);

        // 获取数据库配置
        $config = Config::get('database.connections.mysql');
        $dbName = $config['database'];

        // 显示进度
        $output->writeln('<comment>开始数据库安装...</comment>');

        try {
            // 1. 检查数据库连接
            $output->writeln("\n<comment>[1/3] 检查MySQL服务器连接...</comment>");
            $this->checkMysqlConnection($config);
            $output->writeln('<info>✓ MySQL服务器连接正常</info>');

            // 2. 检查并处理数据库
            $output->writeln("\n<comment>[2/3] 检查数据库连接...</comment>");
            if ($this->checkDatabaseConnection()) {
                $output->writeln('<info>✓ 数据库连接成功</info>');
                // 数据库存在，检查是否为空
                if (!$this->isDatabaseEmpty()) {
                    $output->writeln("\n<error>安装中止：数据库 '{$dbName}' 中存在非空表</error>");
                    $output->writeln('<comment>为确保数据安全，初始化安装需要完全空的数据库</comment>');
                    $output->writeln('<comment>解决方案：</comment>');
                    $output->writeln('1. 请手动清空数据库 ' . $dbName . ' 中的所有表');
                    $output->writeln('2. 或者创建一个新的空数据库并修改配置');
                    throw new \RuntimeException("'{$dbName}' 不是一个空数据库");
                }
            } else {
                // 数据库不存在，创建
                $output->writeln('<comment>数据库不存在，尝试创建...</comment>');
                $this->createDatabase($config, $dbName);
                $output->writeln("<info>✓ 数据库 '{$dbName}' 创建成功</info>");
            }


            //  4. 执行SQL安装
            $output->writeln("\n<comment>[3/3] 执行SQL安装...</comment>");
            $this->executeSqlFile($output);
            $this->verifyInstallation($config);

            // 完成
            $output->writeln("\n<info>数据库安装成功！</info>");
            return 0;
        } catch (\Exception $e) {
            $output->writeln("\n<error>安装失败: " . $e->getMessage() . "</error>");
            return 1;
        }
    }

    /**
     * 环境检查
     */
    protected function checkEnvironment(Output $output): void
    {
        $output->writeln('<comment>正在检查环境...</comment>');
        $passed = true;

        // 检查PHP版本
        if (version_compare(PHP_VERSION, '8.2') < 0) {
            $output->writeln('<error>PHP版本必须 >= 8.2 (当前: ' . PHP_VERSION . ')</error>');
            $passed = false;
        }

        // 检查MySQL扩展
        if (!extension_loaded('pdo_mysql')) {
            $output->writeln('<error>未加载PDO MySQL扩展</error>');
            $passed = false;
        }

        if (!$passed) {
            throw new \RuntimeException('环境检查未通过');
        }
    }


    /**
     * 检查mysql服务器连接
     */
    protected function checkMysqlConnection($config): void
    {
        try {
            // 创建一个DSN字符串，用于PDO连接
            $dsn = "{$config['type']}:host={$config['hostname']};port={$config['hostport']};charset={$config['charset']}";

            // 尝试通过PDO连接到数据库
            $pdo = new \PDO($dsn, $config['username'], $config['password'], $config['params'] ?? []);

            // 设置PDO错误模式为异常，以便捕获连接错误
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            throw new \RuntimeException('数据库连接失败: ' . $e->getMessage());
        }
    }




    /**
     * 检查数据库连接
     */
    protected function checkDatabaseConnection(): bool
    {
        try {
            Db::connect()->query('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 检查数据库是否存在
     */
    protected function databaseExists($dbName): bool
    {
        try {
            $result = Db::connect()->query(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
                [$dbName]
            );
            return !empty($result);
        } catch (\Exception $e) {
            throw new \RuntimeException('数据库检查失败: ' . $e->getMessage());
        }
    }

    /**
     * 检查数据库是否为空
     */
    protected function isDatabaseEmpty(): bool
    {
        try {
            $result = Db::connect()->query("
                SELECT SUM(TABLE_ROWS) as total_rows 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = DATABASE()
            ");
            return $result[0]['total_rows'] == 0;
        } catch (\Exception $e) {
            throw new \RuntimeException('数据库空状态检查失败: ' . $e->getMessage());
        }
    }
    /**
     * 创建数据库
     */
    protected function createDatabase(array $config, string $dbName): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s',
            $config['hostname'] ?? '127.0.0.1',
            $config['hostport'] ?? 3306
        );
        try {

            $pdo = new \PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
            // 检查数据库是否已存在
            $stmt = $pdo->query(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'"
            );

            if ($stmt->rowCount() > 0) {
                throw new \RuntimeException("数据库 '{$dbName}' 已存在");
            }
            // 创建数据库
            $charset = $config['charset'] ?? 'utf8mb4';
            $collation = $config['collation'] ?? 'utf8mb4_general_ci';

            $pdo->exec(
                "CREATE DATABASE `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation}"
            );
            // 验证是否创建成功
            $stmt = $pdo->query(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'"
            );

            if ($stmt->rowCount() === 0) {
                throw new \RuntimeException("数据库 '{$dbName}' 创建验证失败");
            }
        } catch (\PDOException $e) {
            throw new \RuntimeException('数据库操作失败: ' . $e->getMessage());
        }
    }
    /**
     * 执行SQL文件
     */
    protected function executeSqlFile(Output $output): void
    {
        $sqlFile = app()->getRootPath() . 'sql/install.sql';

        if (!file_exists($sqlFile)) {
            throw new \RuntimeException('未找到SQL文件: ' . $sqlFile);
        }

        $sql = file_get_contents($sqlFile);

        $sqlList = array_filter(array_map('trim', explode(';', $sql)));

        $output->writeln("\n<comment>正在执行 " . count($sqlList) . " 条SQL语句...</comment>");

        // SQL执行进度
        $sqlTotal = count($sqlList);
        $sqlCurrent = 0;

        Db::startTrans();
        try {
            foreach ($sqlList as $sql) {
                if (!empty($sql)) {
                    Db::execute($sql);
                    $sqlCurrent++;
                    $this->showProgress($output, $sqlCurrent, $sqlTotal, "执行SQL {$sqlCurrent}/{$sqlTotal}");
                }
            }
            Db::commit();
            $output->writeln(""); // 换行
        } catch (\Exception $e) {
            Db::rollback();
            $output->writeln(""); // 换行
            throw new \RuntimeException('SQL执行失败: ' . $e->getMessage());
        }
    }

    /**
     * 验证安装
     */
    protected function verifyInstallation($config): void
    {
        // 检查核心表是否存在
        $requiredTables = ['user', 'role']; 
        $missingTables = [];

        foreach ($requiredTables as $table) {
            try {
                $dbName = $config['prefix'] . $table;
                Db::query("SELECT * FROM `{$dbName}` LIMIT 1");
            } catch (\Exception $e) {
                $missingTables[] = $table;
            }
        }

        if (!empty($missingTables)) {
            throw new \RuntimeException('缺少必要的表: ' . implode(', ', $missingTables));
        }
    }


    /**
     * 显示进度条
     */
    protected function showProgress(Output $output, $current, $total, $message = '', $barWidth = 50)
    {
        $percent = round(($current / $total) * 100);
        $filled = round(($barWidth * $current) / $total);
        $empty = $barWidth - $filled;

        $bar = str_repeat('=', $filled) . ($current < $total ? '>' : '=') . str_repeat(' ', $empty);
        $output->write(sprintf(
            "\r[%s] %3d%% %s",
            $bar,
            $percent,
            $message
        ));

        if ($current >= $total) {
            $output->writeln('');
        }
    }
}
