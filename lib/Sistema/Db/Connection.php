<?php

class Sistema_Db_Connection
{
    public static function factory($config)
    {
        switch ($config['adapter'])
        {
            case 'mysql':
                return Sistema_Db_Adapter_Mysql::getConnection($config);
                break;
        }
    }
}