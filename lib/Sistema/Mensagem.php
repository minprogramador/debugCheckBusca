<?php

class Sistema_Mensagem extends Sistema_Db_Abstract
{
    protected $_table   = "mensagem";
    private   $data     = null;
    private   $mensagem = null;
    private   $status   = null;
    
    
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    protected function _insert(){}
    
    protected function _update()
    {
        $util = new Sistema_Util();
        $db = $this->getDb();
        $stm = $db->prepare(" update $this->_table set data=:data, mensagem=:mensagem, status=:status where id=:id");
       
        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':data',$util->conData($this->getData()));
        $stm->bindValue(':mensagem',utf8_decode($this->getMensagem()));
        $stm->bindValue(':status',$this->getStatus());
        return $stm->execute();
    }
}