<?php
/**
 * Created by PhpStorm.
 * User: yujiajie
 * Date: 2019/11/21
 * Time: 16:42
 */

namespace core;

class Router
{
    public static $options = array(
        'delimiter' => '/',
        'rules' => array(),
        'accept' => array('controller', 'action'),
        'default' => array('Index', 'index'),
    );

    public static function parse($url = '', array $options = array())
    {
        $options = $options + self::$options;
        if ($url) {
            $url = explode('?', $url, 2);
            $arr = self::parseURL(trim($url[0], '/'), $options) + $options['default']; // + $arr

//            $arr[0] = \ucfirst($arr[0]);

            return $arr;
        }

        $arr = $_GET;

        $c = & $arr[$options['accept'][0]] or $c = $options['default'][0];
        $a = & $arr[$options['accept'][1]] or $a = $options['default'][1];

        return array($c, $a);
    }

    public static function parseURL($url, $options)
    {
        foreach ($options['rules'] as $key => $val) {
            $r = \preg_replace('/^' . $key . '$/i', $val, $url, -1, $n);
            if ($n) {
                $url = $r;
                break;
            }
        }

        return \explode($options['delimiter'], $url);
    }
}