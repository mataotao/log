<?php
namespace App\Repositories;
/**
 * Created by PhpStorm.
 * Date: 2017/6/28
 * Time: 11:05
 * Author: SteveGao
 * Company: Mr.Stock
 */
use App\Entities\Wlzx\WlzxTeacherAsc;
class RedisPredis
{
    /**
     * 给未来之星机构 wlzx_teacher_asc 表中的incr_sort 分配自增的值
     * @return mixed
     */
    static public function assignTeacherAscIncrSort(){
        $key = 'wlzx_teacher_stort'; //客服分配的redis key
        $value =  self::getPredisIncrValue($key);
        if (!$value) {
            $maxId = WlzxTeacherAsc::getMaxIncrSort();
            $sort = $maxId + 1;
            self::setPredisValue($key, $sort);
            return $sort;
        }
        return $value;
    }

    /**
     * 向redis集群写入数据
     * @param $key
     * @param $value
     * @return mixed
     */
    static public function setPredisValue($key, $value)
    {
        $predis = new \CachePredis();
        $result = $predis->sset($key, $value,'',null,'clusterclient');
        return $result;
    }

    /**
     * 从redis集群读取数据
     * @param $key
     * @return mixed
     */
    public static function getPredisVlaue($key)
    {
        $predis = new \CachePredis();
        $result = $predis->get($key, '','clusterclient',0,0);
        return $result;
    }

    /**
     * 从集群读取自增字段
     * @param $key
     * @return mixed
     */
    public static function getPredisIncrValue($key)
    {
        $predis = new \CachePredis();
        $result = $predis->incr($key, 'clusterclient', 0);
        return $result;
    }
}