<?php
namespace core\traits;
use think\facade\Db;
/**
 * 事务操作
 * trait TransTrait
 * @package core\traits
 */
trait TransTrait
{

    /**
     * 操作数据库事务,当闭包中的代码发生异常会自动回滚
     * @param \Closure $function
     * @return void
     */
    public function transaction(\Closure $function)
    {   
        try {
            return Db::transaction($function);
        } catch (\Exception  | \Throwable $e) {
            throw $e;
        }
    }


    /**
     * 开启事务
     * 
     * @return void
     */
    public function startTrans()
    {
        Db::startTrans();
    }

    /**
     * 提交事务
     * 
     * @return void
     */
    public function commit()
    {
        Db::commit();
    }

    /**
     * 回滚事务
     * 
     * @return void
     */
    public function rollback()
    {
        Db::rollback();
    }


}
