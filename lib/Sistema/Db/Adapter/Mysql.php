<?php

class Sistema_Db_Adapter_Mysql implements Sistema_Db_Adapter_Interface
{
    private static $instance;
    public static function getConnection($config)
    {
        if(!isset(self::$instance))
        {
            $dsn = $config['adapter'] . ":host=" . $config['hostname'] . ";dbname=" . $config['dbname'];
            try
            {
                self::$instance = new PDO($dsn,$config['user'],$config['password']);
            }
            catch (PDOException $e)
            {
                echo ($e->getMessage());
                die;
            }
        }
        return self::$instance;
    }
}