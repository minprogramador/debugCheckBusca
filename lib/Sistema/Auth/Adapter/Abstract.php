<?php

abstract class Sistema_Auth_Adapter_Abstract
{
    protected $user     = null;
    protected $password = null;
    public function getUser()
    {
        return $this->user;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    abstract public function autenticate();
}