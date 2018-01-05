<?php
namespace Tanel\Counter;

use Tanel\Counter\Counter;

/**
 * 静态计数器功能
 * @author tanda <tanda@wondershare.cn>
 */
class RedisCounter implements Counter {
    /**
     * 实例化计数器
     * @var string
     */
    private $key = '';

    /**
     * 强制更新
     * @var string
     */
    private static $force = 'force_update';

    /**
     * redis链接
     * @var [type]
     */
    private static $redis;

    /**
     * 计数器值
     * @var int
     */
    private static $counter = 'TANEL_STATIC_COUNTER';

    /**
     * redis连接
     * @return [type] [description]
     */
    protected static function getInstance($args) {
        if ($args instanceof Redis) {
            return self::$redis = $args;
        }

        /**
         * array => ['host' => '127.0.0.1', 'port' => '6379', 'force_update' => false]
         */
        if (is_array($args)) {
            //如果不强制更新Redis实例则复用前面的
            if(self::$redis && (!isset($args[self::$force]) || $args[self::$force] == false)) {
                return self::$redis;
            }

            //实例化Redis
            self::$redis = new \Redis();
            try {
                self::$redis->connect($args['host'], $args['port']);
                self::$redis->ping();
            } catch (Exception $e) {
                throw new Exception("RedisHandle_redis_connect Error " . $e->getMessage());
                return false;
            }

            return self::$redis;
        } else {
            throw new Exception("RedisHandle_is_not_found");
            return false;
        }
    }

    /**
     * 构造函数
     * @param string $key  [description]
     * @param object|array $args 额外参数
     */
    public function __construct($key, $args) {
        self::getInstance($args);
        $this->key = $key ? $key : uniqid();

        return $this;
    }

    /**
     * 初始化
     * @implement
     * @return
     */
    public function initialize() {
        return self::$redis->hSet(self::$counter, $this->key, 0);
    }

    /**
     * 统计计数器大小
     * @implement
     * @return [type]      [description]
     */
    public function getCounter() {
        return self::$redis->hGet(self::$counter, $this->key);
    }

    /**
     * 计数器递增
     * @implement
     * @param  integer $value 递增的值
     * @return interger
     */
    public function increament($value = 1) {
        return self::$redis->hIncrBy(self::$counter, $this->key, $value);
    }

    /**
     * 计数器递减
     * @implement
     * @param  integer $value 递减的值
     * @return integer
     */
    public function decreament($value = 1) {
        $handler = self::$redis;
        if ($now = $handler->hGet(self::$counter, $this->key)) {
            return $handler->hIncrBy(self::$counter, $this->key, (-1) * $value);
        }
        return $now;
    }

    /**
     * 销毁计数器
     * @implement
     * @return
     */
    public function destory() {
        return self::$redis->hDel(self::$counter, $this->key);
    }
}
