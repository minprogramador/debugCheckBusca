<?php

interface Sistema_Auth_Interface{    public function write(Sistema_Auth_Adapter_Abstract $adapter);    public function isLogged();    public function logout();}