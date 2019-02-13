<?php
namespace think\response;
/**
 *  @file : Printr.php
 *  @brief: 方便控制台下打印变量
 */
class Printr extends \think\Response {

    protected $contentType = 'text/plain';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        return print_r($data, true);
    }

}
