<?php

#Criado em: 19/12/2012
#brunno duarte.
#by puttyoe@hotmail.com

class Sistema_Sec extends Sistema_Db_Abstract
{
    protected $_table  = "control_sec";
	private   $limite  = null;
	private   $usado   = null;
    private   $usuario = null;
    public   $status  = null;
	public   $servico = null;
	
	public function setServico($servico)
	{
		$this->servico = $servico;
	}
	
	public function getServico()
	{
		return $this->servico;
	}
	
    public function getLimite(){ return $this->limite; }

    public function setLimite($limite){ $this->limite = $limite; }

    public function getUsado(){ return $this->usado; }

    public function setUsado($usado){ $this->usado = $usado; }

    public function getUsuario(){ return $this->usuario; }

    public function setUsuario($usuario){ $this->usuario = $usuario; }

    public function getStatus(){ return $this->status; }

    public function setStatus($status){ $this->status = $status; }

    protected function _insert()
	{
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,servico,limite,usado,status) Values (:id,:servico,:limite,:usado,:status)');
		
        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':servico',$this->getServico());
        $stm->bindValue(':status', 1);
        return $stm->execute();
	}

    public function Cadastrar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,servico,limite,usado,status) Values (:id,:servico,:limite,:usado,:status)');

        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':servico',$this->getServico());

      	$stm->bindValue(':status', '1');
        return $stm->execute();
    }
	
    protected function _update()
	{
		$db = $this->getDb();
		
		if(strlen($this->status) > 0)
		{
			$stm = $db->prepare(" update `$this->_table` set limite=:limite, usado=:usado,status=:status where servico=:servico and id_usuario=:id");
		}
		else
		{
			$stm = $db->prepare(" update `$this->_table` set limite=:limite, usado=:usado where servico=:servico and id_usuario=:id");
		}
	
		$stm->bindValue(':id', $this->getId());
		$stm->bindValue(':limite',$this->getLimite());
		$stm->bindValue(':usado',$this->getUsado());
		$stm->bindValue(':servico',$this->getUsado());
		
		if(strlen($this->status) > 0)
		{
			$stm->bindValue(':status',  $this->getStatus());
		}
		
		return $stm->execute();
	}
	
	public function atualizar($id,$limite,$usado,$servico,$status=0)
	{
		$db = $this->getDb();
		
		if(strlen($status) > 0)
		{
			$stm = $db->prepare(" update `$this->_table` set limite=:limite, usado=:usado,status=:status where servico=:servico and id_usuario=:id");
		}
		else
		{
			$stm = $db->prepare(" update `$this->_table` set limite=:limite, usado=:usado where servico=:servico and id_usuario=:id");
		}
	
		$stm->bindValue(':id', $id);
		$stm->bindValue(':limite',$limite);
		$stm->bindValue(':usado',$usado);
		$stm->bindValue(':servico',$servico);
		
		if(strlen($status) > 0)
		{
			$stm->bindValue(':status',  $status);
		}
		
		return $stm->execute();
	}
	
	public function listPontos($servico = null)
	{
		// id - id_usuario - [ servico ] - limite - usado - status 
        $db  = $this->getDb();
        
		
		$stm = $db->prepare("select * from `".$this->_table."` where servico=:servico and id_usuario=:id");

        $stm->bindValue(':id',      $this->getId());
        $stm->bindValue(':servico', $servico);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,servico,limite,usado,status) Values (:id,:servico,:limite,:usado,:status)');
			
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':servico', $servico);
			$stm->bindValue(':limite', 2);
			$stm->bindValue(':usado',  0);
			$stm->bindValue(':status', 1);
			$stm->execute();

			$db  = $this->getDb();
			$stm = $db->prepare("select * from `".$this->_table."` where servico=:servico and id_usuario=:id");
	
			$stm->bindValue(':id',      $this->getId());
			$stm->bindValue(':servico', $servico);
			$stm->execute();
			$result = $stm->fetch(PDO::FETCH_ASSOC);
		}
		
		return $result;
	}
    
    public function Permissao()
    {        
        $usuario = new Sistema_Usuarios();
        $usuario->setUsuario($_SESSION['getUsuario']);
        $res = $usuario->getRes();

        $result = $this->getRes($res['id']);
		
        $db1  = $this->getDb();
        $stm1 = $db1->prepare("select * from `$this->_table` where id_usuario=:id");

        $stm1->bindValue(':id', $res['id']);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
		
		
		if(count($result1) == 1)
		{
			if(count($result) == 1)
			{
				$db  = $this->getDb();
				$stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
				$stm->bindValue(':id',     $res['id']);
				$stm->bindValue(':limite', "2");
				$stm->bindValue(':usado',  "0");
				$stm->bindValue(':status', "1");
				$stm->execute();
			}
		}
		else
		{
			if($result1['limite'] <= $result1['usado'])
        	{
            	die("Limite atingido, entre em contato");
        	}
		}
		
    }
	
    public function CountPontos($servico)
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `'.$this->_table.'` set usado=(usado+1) where servico=:servico and id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->bindValue(':servico', $servico);
        $stm->execute();
    }
	
    public function getRes($id=null)
    {
        $db  = $this->getDb();
        if(isset($id))
        {
            $stm = $db->prepare("select * from `'.$this->_table.'` where id_usuario=:id");
            $stm->bindValue(':id', $id);
        }
        else
        {
            $stm = $db->prepare("select * from `'.$this->_table.'` where id=:id");
            $stm->bindValue(':usuario', $this->getId());
        }
		
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }

    public function insertLogCondutor($doc,$resultado,$id)
    {
    	
		$db  = $this->getDb();
		$stm = $db->prepare(' insert into `log_condutor` (id_usuario,doc,resultado,data) Values (:id_usuario,:doc,:resultado,:data)');
						
		$stm->bindValue(':id_usuario', $id);
		$stm->bindValue(':doc',        $doc);
		$stm->bindValue(':resultado',  $resultado);
		$stm->bindValue(':data',       date("Y-m-d H:i:s"));
		$stm->execute();
		
    }

    public function insertLogVeiculo($tipo,$doc,$resultado,$id)
    {
    	$db  = $this->getDb();
		$stm = $db->prepare("select * from `log_veiculo` where doc=:doc");
	
		$stm->bindValue(':doc', $doc);
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		
		if(isset($resultado))
		{
			if(count($result) == '1')
			{
		    	$db  = $this->getDb();
				$stm = $db->prepare(' insert into `log_veiculo` (id_usuario,tipo,doc,resultado,data) Values (:id_usuario,:tipo,:doc,:resultado,:data)');
					
				$stm->bindValue(':id_usuario', $id);
				$stm->bindValue(':tipo',       $tipo);
				$stm->bindValue(':doc',        $doc);
				$stm->bindValue(':resultado',  $resultado);
				$stm->bindValue(':data',       date("Y-m-d H:i:s"));
				$stm->execute();
			}
		}
		else
		{
			if(count($result) > '1')
			{
				return $result;
			}
			else
			{
				return false;
			}
		}
    }    
}