<?php

function param($param_name, $default = NULL) {
    if (isset($_REQUEST[$param_name])) {
        return $_REQUEST[$param_name];
    }
    if (!($default === NULL)) {
        return $default;
    }
    return NULL;
}

function is_today($t) {
    if (date('Y-m-d') == date('Y-m-d', $t)) {
        return true;
    }
    return false;
}

function output($response) {
    echo json_encode($response);
    exit(1);
}

function localRedisConnect()
{
    $redis = new Redis();
    try {
        global $conf;
        $redis->connect($conf['redis']['ip'], $conf['redis']['port']);
        if ($conf['redis']['password']) $redis->auth($conf['redis']['password']);
    }catch (Exception $e) {
        output(array('ret' => 'redis disconnect'));
    }
    return $redis;
}

function writeLog($file = 'error', $message) {
    $file = ROOTPATH . 'Log' . DIRECTORY_SEPARATOR . $file.'_'.TODAY . '.log';

    $ret = \error_log(DATETIME.":\t".json_encode($message).PHP_EOL, 3, $file);

    if ($ret) return true;

    return false;
}