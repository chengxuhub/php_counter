<?php
namespace Tanel\Counter;

use Tanel\Counter\Counter;

/**
 * 静态计数器功能
 * @author tanda <tanda@wondershare.cn>
 */
class LocalCounter implements Counter {
    private static $counter = [];

    /**
     * 计数器名称
     * @var string
     */
    private $key = '';

    public function __construct($key) {
        $this->key = $key ? $key : uniqid();
        return $this;
    }

    /**
     * 初始化
     * @return
     */
    public function initialize() {
        return self::$counter[$this->key] = 0;
    }

    /**
     * 统计
     * @return
     */
    public function getCounter() {
        return isset(self::$counter[$this->key]) ? self::$counter[$this->key] : false;
    }

    /**
     * 计数器递增
     * @param  integer $value 递增的值
     * @return interger
     */
    public function increament($value = 1) {
        if (!isset(self::$counter[$this->key])) {
            self::initialize($this->key);
        }
        return self::$counter[$this->key] = self::$counter[$this->key] + $value;
    }

    /**
     * 计数器递减
     * @return integer
     */
    public function decreament($value = 1) {
        if (!isset(self::$counter[$this->key])) {
            self::initialize($this->key);
        }
        self::$counter[$this->key] = (self::$counter[$this->key] >= $value) ? self::$counter[$this->key] - $value : 0;
        return self::$counter[$this->key];
    }

    /**
     * 销毁计数器
     * @return
     */
    public function destory() {
        if (isset(self::$counter[$this->key])) {
            unset(self::$counter[$this->key]);
            return true;
        } else {
            return null;
        }
    }
}