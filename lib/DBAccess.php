<?php
/**
 * Created by PhpStorm.
 * User: yujiajie
 * Date: 2019/11/19
 * Time: 16:30
 */

class DBAccess
{
    private $link;

    public function __construct($db_conf)
    {
        $dsn = $db_conf['driver'].':host='.$db_conf['host'].';dbname='.$db_conf['database'];
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];
        $this->link = new PDO($dsn, $db_conf['usr'], $db_conf['pass'], $options);

        if (!$this->link) {
            die('DATABASE CONNECT ERROR');
        }

        $this->link->exec("SET NAMES '" . $db_conf['encoding'] . "'");
        $this->link->exec("SET sql_mode=''");
//        $this->link->exec("SET time_zone='" . $db_conf['time_zone'] . "';");

    }

    public function select($sql, $params) {
        $statement = $this->link->prepare($sql);
        $statement->execute($params);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        unset($statement);
        return $result;
    }

    public function select_row($sql, $params) {
        $statement = $this->link->prepare($sql);
        $statement->execute($params);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        unset($statement);
        return $result;
    }

}

function dba() {
    static $dba;
    if (!$dba) {
        global $db_config;
        $dba = new DBAccess($db_config['db_gm']);
    }

    return $dba;
}