<?php

class Sistema_Configuracao extends Sistema_Db_Abstract
{
    protected $_table  = "config";
    private   $nome    = null;
    private   $url     = null;
    private   $login   = null;
    private   $captcha = null;
    private   $status  = null;
    
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    protected function _insert()
    {
        
    }
    
    protected function _update()
    {
        $db = $this->getDb();
        $stm = $db->prepare(" update $this->_table set nome=:nome, url=:url, login=:login, captcha=:captcha, status=:status where id=:id");
       
        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':nome',$this->getNome());
        $stm->bindValue(':url',$this->getUrl());
        $stm->bindValue(':login',$this->getLogin());
        $stm->bindValue(':captcha',$this->getCaptcha());
        $stm->bindValue(':status',$this->getStatus());
        
        return $stm->execute();
    }
    
    public function getDados()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("SELECT * from `config`");#".$this->_table);

        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        	
        return $result;
    }
}