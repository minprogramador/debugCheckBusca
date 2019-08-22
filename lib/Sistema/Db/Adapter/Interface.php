<?php

interface Sistema_Db_Adapter_Interface
{
    public static function getConnection($config);
}