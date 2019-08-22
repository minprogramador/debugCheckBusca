<?php

class Sistema_Auth implements Sistema_Auth_Interface
{
    private static $instance;
    public static function getInstance()
    {
        if(!isset(self::$instance))
        {
            self::$instance = new Sistema_Auth();
        }
        return self::$instance;
    }
    public function write(Sistema_Auth_Adapter_Abstract $adapter)
    {
        if ($adapter->autenticate())
        {
            $_SESSION['Sistema_Auth']['auth'] = true;
            $_SESSION['Sistema_Auth']['user'] = $adapter->getUser();
            return true;
        }
        else
            return false;
    }
    public function isLogged()
    {
        if (isset($_SESSION['Sistema_Auth']['auth']))
            return true;
        else
            return false;
    }
    public function logout()
    {
        unset($_SESSION['Sistema_Auth']['auth']);
        unset($_SESSION['Sistema_Auth']['user']);
    }
}