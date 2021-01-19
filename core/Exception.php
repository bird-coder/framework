<?php
/**
 * Created by PhpStorm.
 * User: yujiajie
 * Date: 2019/11/21
 * Time: 18:56
 */

namespace core;


class Exception extends \Exception
{
    public static $php_errors = array(
        \E_ERROR => 'Error',
        \E_WARNING => 'Warning',
        \E_PARSE => 'Parse Error',
        \E_NOTICE => 'Notice',
        \E_CORE_ERROR => 'Core Error', // since PHP 4
        \E_CORE_WARNING => 'Core Warning', // since PHP 4
        \E_COMPILE_ERROR => 'Compile Error', // since PHP 4
        \E_COMPILE_WARNING => 'Compile Warning', // since PHP 4
        \E_USER_ERROR => 'User Error', // since PHP 4
        \E_USER_WARNING => 'User Warning', // since PHP 4Parse Error
        \E_USER_NOTICE => 'User Notice', // since PHP 4
        \E_STRICT => 'Strict Notice', // since PHP 5
        \E_RECOVERABLE_ERROR => 'Recoverable Error', // since PHP 5.2.0
        \E_DEPRECATED => 'Deprecated', // Since PHP 5.3.0
        \E_USER_DEPRECATED => 'User Deprecated', // Since PHP 5.3.0
    );

    /**
     * @static
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @return void
     * @throws \ErrorException
     */
    public static function error($code, $message, $file, $line)
    {
        $e = new \ErrorException($message, $code, 0, $file, $line);

        if (\error_reporting())
            throw $e;
        else
            self::log($e);
    }

    /**
     * @param \Exception $e
     * @return void
     */
    public static function handler(\Exception $e)
    {
        try {
            self::log($e);

            $code = 500;
            if ($e->getCode() == 404) $code = 404;
            Response::httpStatus($code);
        } catch (\Exception $e) {
            print_r($e->getTrace());
            exit(1);
        }
    }

    /**
     * @param int $code
     * @return mixed
     */
    public static function getCodeValue($code)
    {
        if (isset(self::$php_errors[$code]))
            return self::$php_errors[$code];

        return $code;
    }

    /**
     * @param \Exception $e
     * @return string
     */
    public static function log($e)
    {
        $message = self::text($e);
        writeLog('error', $message);
    }

    /**
     * @param \Exception $e
     * @param string $format
     * @return string
     */
    public static function text(\Exception $e, $format = '[%s] [%s] %s: %d')
    {
        return \sprintf($format, self::getCodeValue($e->getCode()), $e->getMessage(), $e->getFile(), $e->getLine());
    }
}