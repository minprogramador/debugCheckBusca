<?php

#Criado em: 08/02/2013
#brunno duarte.
#by puttyoe@hotmail.com

class Sistema_FullBusk extends Sistema_Db_Abstract
{
    protected $_table  = "control_fbusk";
	private   $limite  = null;
	private   $usado   = null;
    private   $usuario = null;
	
    public function getLimite(){ return $this->limite; }

    public function setLimite($limite){ $this->limite = $limite; }

    public function getUsado(){ return $this->usado; }

    public function setUsado($usado){ $this->usado = $usado; }

    public function getUsuario(){ return $this->usuario; }

    public function setUsuario($usuario){ $this->usuario = $usuario; }

    protected function _insert()
	{
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
		
        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':status', 1);
        return $stm->execute();
	}

    public function Cadastrar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `'.$this->_table.'` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');

        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':status', '1');

        return $stm->execute();
    }
	
    protected function _update()
	{
        $db = $this->getDb();
        $stm = $db->prepare(" update `$this->_table` set limite=:limite, usado=:usado where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':limite',$this->getLimite());
        $stm->bindValue(':usado',$this->getUsado());
        return $stm->execute();
	}
	
	public function upInss($limite,$usado)
	{
        $db = $this->getDb();
        $stm = $db->prepare(" update `control_inss` set limite=:limite, usado=:usado where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':limite',$limite);
        $stm->bindValue(':usado',$usado);
        return $stm->execute();
	}

	public function upConfirme($limite,$usado)
	{
        $db = $this->getDb();
        $stm = $db->prepare(" update `control_confirme` set limite=:limite, usado=:usado where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':limite',$limite);
        $stm->bindValue(':usado',$usado);
        return $stm->execute();
	}

	public function upPai($limite,$usado)
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_pai` where id_usuario=:id");

		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		$ver = count($result);
		
		if($ver == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_pai` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		else
		{
			$db = $this->getDb();
			$stm = $db->prepare(" update `control_pai` set limite=:limite, usado=:usado where id_usuario=:id");

			$stm->bindValue(':id', $this->getId());
			$stm->bindValue(':limite',$limite);
			$stm->bindValue(':usado',$usado);
			return $stm->execute();
		}
	}
	
	public function listPontos()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `".$this->_table."` where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function listPontosInss()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `control_inss` where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_inss` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "1000");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		
		return $result;
	}

	public function listPontosConfirme()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `control_confirme` where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_confirme` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "500");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		
		return $result;
	}

	public function listPontosPai()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `control_pai` where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_pai` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
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
				$stm->bindValue(':limite', "10");
				$stm->bindValue(':usado',  "0");
				$stm->bindValue(':status', "1");
				$stm->execute();
			}
		}
		else
		{
			if($result1['limite'] <= $result1['usado'])
        	{
            	header("Location:".PATCH.'/?limiteRg');
            	die;
        	}
		}
		
    }
	
    public function CountPontos()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `'.$this->_table.'` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
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
}