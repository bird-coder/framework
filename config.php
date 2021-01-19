<?php

define('ROOTPATH', __DIR__.DIRECTORY_SEPARATOR);
define('TIMESTAMP', time());
define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));
define('TODAY', date('Y-m-d', TIMESTAMP));

$conf = array(
    'redis_key_list' => 'list_',
    'redis_key_list_expire' => 300,
    'redis_key_h5activity' => 'h5activity_',
    'cdn_url' => 'xxxx',
    'redis' => [
        'ip' => 'xxxx',
        'port' => 6381,
        'password' => 'xxxx'
    ],
);

$db_config = array(
    'db_gm' => array(
        'driver' => 'mysql',
        'persistent' => false,
        'host' => 'xxxx',
        'usr' => 'xxxx',
        'pass' => 'xxxx',
        'database' => 'xxxx',
        'encoding' => "utf8", //OUTPUT_CHARSET==UTF8_CHARSET?"utf8":OUTPUT_CHARSET
    ),
);
