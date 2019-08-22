<?php

class Sistema_Verificacao extends Sistema_Db_Abstract
{
    protected $_table  = 'limit_consults';
    private   $usado   = null;
    private   $limite  = null;
    private   $servico = null;
    private   $data    = null;
    private   $ulData  = null;
    private   $status  = null;
    
    public function getUsado()
    {
        return $this->usado;
    }

    public function setUsado($usado)
    {
        $this->usado = $usado;
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        $this->limite = $limite;
    }
    
    public function setServico($servico)
    {
        $this->servico = $servico;
    }
    
    public function getServico()
    {
        return $this->servico;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getUlData()
    {
        return $this->ulData;
    }

    public function setUlData($ulData)
    {
        $this->ulData = $ulData;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    protected function _insert() {}

    protected function _update(){}
    
    public function Alterar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(" update $this->_table set usado=:usado, limite=:limite, tempo=:tempo, servico=:servico, ul_data=:udata, status=:status where id=:id");
        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':usado',$this->getUsado());
        $stm->bindValue(':limite',$this->getLimite());
        $stm->bindValue(':servico',$this->getServico());
        $stm->bindValue(':udata',$this->getUlData());
        $stm->bindValue(':tempo',$this->getData());
        $stm->bindValue(':status',$this->getStatus());    
        return $stm->execute();
    }
    
    public function getRes()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from $this->_table where servico=:servico");
        $stm->bindValue(':servico', $this->getServico());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getResid()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from $this->_table where id=:id");
        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function Computa()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("update $this->_table set usado = usado + 1 where servico=:servico");
        $stm->bindValue(':servico',$this->getServico());
        return $stm->execute();
    }
    
    public function Verifica()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from $this->_table where servico=:servico");
        $stm->bindValue(':servico', $this->getServico());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        die;
        //return $result;
    }
    
    public function limpa()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("update ".$this->_table." SET usado= :usado, ul_data= :ul_data where servico= :servico");
        $stm->bindValue('usado','0');
        $stm->bindValue('ul_data',date("Y-m-d H:i:s"));
        $stm->bindValue(':servico',$this->getServico());
        return $stm->execute();
    }
}