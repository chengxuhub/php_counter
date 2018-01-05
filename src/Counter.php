<?php
namespace Tanel\Counter;

use Tanel\Counter\Counter;

/**
 * 静态计数器功能接口
 * @author tanda <tandamailzone@gmail.cn>
 */
Interface Counter {
    public function initialize();
    public function getCounter();
    public function increament($value = 1);
    public function decreament($value = 1);
    public function destory();
}