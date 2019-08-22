<?php

/*
 * Class Online
 * Responsavel por gerir os usuarios Online
 * Versao: 1.2
 * criada em 14/10/2011
 * Desenvolvedor: Brunno duarte.
 * contato: brunos.duarte@hotmail.com
 */
 
class Sistema_Online extends Sistema_Db_Abstract
{   
    protected $_table = 'online';
    private $ip       = null;
    private $tempo    = null;
    private $usuario  = null;
    private $host     = null;
	
    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }
	
    public function getHost()
    {
        return $this->host;
    }
	
    public function getTempo()
    {
        return $this->tempo;
    }

    public function setTempo($tempo)
    {
        $this->tempo = $tempo;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }
    
    public function Ver()
    {
        $db    = $this->getDb();
        $stm   = $db->prepare("select * from $this->_table where usuario=:usuario");
        $stm->bindValue(':usuario', $this->getUsuario());
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return count($res);
    }

    public function Verifica()
    {
        $this->CountData();
        $ip    = $_SERVER['REMOTE_ADDR'];
		$host  = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$tempo = time();       

        $db    = $this->getDb();
        $stm   = $db->prepare("select * from 
                 $this->_table where ip=:ip ORDER BY id DESC");

        $stm->bindValue(':ip', $ip);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);

        if(count($res) == 1)
		{
			if(strlen($this->getUsuario()) > 0)
            {
                $db  = $this->getDb();
				$stm = $db->prepare(' insert into '.$this->_table.' (ip,usuario,tempo,host) Values (:ip,:usuario,:tempo,:host)');
				$stm->bindValue(':ip',$ip);
				$stm->bindValue(':host',$host);
				$stm->bindValue(':usuario',$this->getUsuario());
				$stm->bindValue(':tempo',$tempo);
				$stm->execute();
				unset($db);
				unset($stm);
				return true;
			}
		}
		else
		{
            if(strlen($this->getUsuario()) > 0)
            {
                $db  = $this->getDb();
				$stm = $db->prepare(' update '.$this->_table.' set tempo=:tempo where ip=:ip and usuario=:usuario');
				$stm->bindValue(':tempo',$tempo);            
				$stm->bindValue(':ip',$ip);
				$stm->bindValue(':usuario',$this->getUsuario());
				$stm->execute();
				return true;
            }
		}
	}

    public function CountData()
    {
        $tempo = time();
        $db    = $this->getDb();
        $stm   = $db->prepare("select * from $this->_table WHERE tempo <'$tempo'".-"60");

        $stm->execute();
        $r = $stm->fetchAll(PDO::FETCH_ASSOC);

        if(count($r) >= 1)
        {
            $db  = $this->getDb();
            $stm = $db->prepare(' DELETE from '.$this->_table.' WHERE tempo < "'.$tempo.'"'.-'60');
            $stm->execute();
        }
    }
    
    public function Listar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table");

        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
    
    public function _insert() 
    {
 
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (ip,usuario,tempo,host) 
                            Values (:ip,:usuario,:tempo,:host)');
        
        $stm->bindValue(':ip',$this->getIp());
        $stm->bindValue(':usuario',$this->getUsuario());
        $stm->bindValue(':tempo',$this->getTempo());
        $stm->bindValue(':host',$this->getHost());
        $stm->execute();

        unset ($db);
        unset($stm);
    }

	public function _update() {}  

    public function logout()
    {
		$ip    = $_SERVER['REMOTE_ADDR'];
		if(strlen($this->getUsuario()) <= 2)
		{
			$db    = $this->getDb();
            $stm   = $db->prepare("select * from ".$this->_table." WHERE ip=:ip");
            $stm->bindValue(':ip',$ip);
            $stm->execute();
            
            foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $res);
            $db  = $this->getDb();
            $stm = $db->prepare(' DELETE from '.$this->_table.' WHERE ip=:ip and usuario=:login');
            $stm->bindValue(':ip',$ip);            
            $stm->bindValue(':login',$res['usuario']);            
            $stm->execute();
		}
		else
		{
            $db  = $this->getDb();
            $stm = $db->prepare(' DELETE from '.$this->_table.' WHERE ip=:ip usuario=:login');
            $stm->bindValue(':ip',$ip);            
            $stm->bindValue(':login',$this->getUsuario());       
            $stm->execute();
		}
    }
	
	public function Deslogar()
	{
		$db  = $this->getDb();
		$stm = $db->prepare(' DELETE from '.$this->_table.' WHERE ip=:ip and usuario=:usuario');
		$stm->bindValue(':ip',$this->getIp());            
		$stm->bindValue(':usuario',$this->getUsuario());            
		$stm->execute();
	}
	
    public function Scan()
    {
    	$tempo = time();
        
        $db    = $this->getDb();
        $stm   = $db->prepare("select * from ".$this->_table." WHERE tempo < $tempo  - 120");
        $stm->execute();
        $r = $stm->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($r) > 0)
        {
            foreach($r as $rs)
            {
                $usuario = $rs['usuario'];
                $db  = $this->getDb();
                $stm = $db->prepare(' DELETE from '.$this->_table.' WHERE tempo < "'.$tempo.'"'.-'120');
                $stm->execute();
               
                 unset ($db);
                 unset($stm);
             }
        }
    }
}