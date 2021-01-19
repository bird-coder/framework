<?php
/**
 * Created by PhpStorm.
 * User: yujiajie
 * Date: 2019/11/21
 * Time: 17:39
 */

namespace service;

class Basic
{
    public function __call($name, $arguments)
    {
        throw new \Exception('Action "' . $name . '" not found', 404);
    }
}