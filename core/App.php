<?php
/**
 * Created by PhpStorm.
 * User: yujiajie
 * Date: 2019/11/21
 * Time: 16:33
 */
namespace core;

spl_autoload_register('\core\App::autoload');

class App
{
    public static function run() {
        self::init();
        self::dispatch(self::getPathInfo());
    }

    public static function init() {
        \set_error_handler('\core\Exception::error');
        \set_exception_handler('\core\Exception::handler');
    }

    public static function getPathInfo() {
        if (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) return $_SERVER['PATH_INFO'];
        if (isset($_SERVER['REQUEST_URI']) && trim($_SERVER['REQUEST_URI'], '/')) return str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
        return str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
    }

    public static function dispatch($url) {
        $url = explode('?', $url, 2);
        $key = trim($url[0], '/');
//        $query = Router::parse($url);

        global $router;
//        $key = $query[0].'/'.$query[1];
        if (!isset($router[$key])) throw new \Exception('Router "'.$key.'" not exist', 404);
        define('CONTROLLER', $router[$key]['c']);
        define('ACTION', $router[$key]['a']);

        return self::getController(CONTROLLER)->{ACTION}();
    }

    public static function getController($name)
    {
        $class = 'service\\' . $name;

        if (\class_exists($class))
            return new $class;

        throw new \Exception('Controller "' . $name . '" not found', 404);
    }

    public static function import($name, $throw = true)
    {
        $name = self::parseName($name);
        if (\is_file($name))
            return include $name;

        if ($throw)
            throw new \Exception('File "' . $name . '" is not exists', 404);

        return false;
    }

    public static function parseName($name)
    {
        return \strtr($name, ['\\' => DIRECTORY_SEPARATOR]);
    }

    public static function autoload($class)
    {
        return self::import($class . '.php', true);
    }
}
