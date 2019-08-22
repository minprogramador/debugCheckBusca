<?php

class Sistema_Logs extends Sistema_Db_Abstract
{
    protected $_table    = "log_acesso";
    private   $usuario   = null;
    private   $data      = null;
    private   $ip        = null;
    private   $host      = null;
    private   $navegador = null;
    
    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getNavegador()
    {
        return $this->navegador;
    }

    public function setNavegador($navegador)
    {
        $this->navegador = $navegador;
    }

    protected function _insert()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (usuario,data,ip,host,navegador) Values (:usuario,:data,:ip,:host,:navegador)');
		
        $stm->bindValue(':usuario',   $this->getUsuario());
        $stm->bindValue(':data',      date("Y-m-d H:i:s"));
        $stm->bindValue(':ip',        $_SERVER['REMOTE_ADDR']);
        $stm->bindValue(':host',      gethostbyaddr($_SERVER['REMOTE_ADDR']));
        $stm->bindValue(':navegador', $_SERVER['HTTP_USER_AGENT']);

        return $stm->execute();
    }
    
    protected function _update(){}
	
	public function dellFull()
	{
		$db  = $this->getDb();
        $stm = $db->prepare(' delete from '.$this->_table);
        return $stm->execute();
	}

    public function deleteCondutor()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_condutor` where id=:id');
        $stm->bindValue(':id',   $this->id);
        return $stm->execute();
    }

    public function dellFullCondutor()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_condutor`');
        return $stm->execute();
    }

    //////
    
    public function deleteVeiculos()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_veiculo` where id=:id');
        $stm->bindValue(':id',   $this->id);
        return $stm->execute();
    }

    public function dellFullVeiculos()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_veiculo`');
        return $stm->execute();
    }

    public function selecMesCondutor($mes,$id)
    {
        $db  = $this->getDb();
        if($id != null)
        {
            $stm = $db->prepare("select * from `log_condutor` where MONTH(data)=:mes and id_usuario=$id");
        }
        else
        {
            $stm = $db->prepare("select * from `log_condutor` where MONTH(data)=:mes");
        }
        $stm->bindValue(':mes', $mes);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function selecMesVeiculos($mes,$id)
    {
        $db  = $this->getDb();
        if($id != null)
        {
            $stm = $db->prepare("select * from `log_veiculo` where MONTH(data)=:mes and id_usuario=$id");
        }
        else
        {
             $stm = $db->prepare("select * from `log_veiculo` where MONTH(data)=:mes");           
        }
        $stm->bindValue(':mes', $mes);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //serasa
    public function selecMesSerasa($mes,$id)
    {
        $db  = $this->getDb();
        if($id != null)
        {
            $stm = $db->prepare("select * from `log_serasa` where MONTH(data)=:mes and id_usuario=$id");
        }
        else
        {
             $stm = $db->prepare("select * from `log_serasa` where MONTH(data)=:mes");           
        }
        $stm->bindValue(':mes', $mes);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function deleteSerasa()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_serasa` where id=:id');
        $stm->bindValue(':id',   $this->id);
        return $stm->execute();
    }

    public function dellFullSerasa()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from `log_serasa`');
        return $stm->execute();
    }

    public function insertLogSerasa($servico,$doc,$id)
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `log_serasa` (id_usuario,servico,doc,data) Values (:id_usuario,:servico,:doc,:data)');
                    
        $stm->bindValue(':id_usuario', $id);
        $stm->bindValue(':servico',    $servico);
        $stm->bindValue(':doc',        $doc);
        $stm->bindValue(':data',       date("Y-m-d H:i:s"));
        $stm->execute();
    }    
}