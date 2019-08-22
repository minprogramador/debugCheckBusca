<?php

class Sistema_Faturas extends Sistema_Db_Abstract
{
    protected $_table     = 'fatura';
    private   $usuario    = null;
	private   $fatura     = null;
    private   $data       = null;
    private   $vencimento = null;
    private   $pagamento  = null;
    private   $periodo    = null;
    private   $valor      = null;
    private   $forma      = null;
    private   $tipo       = null;
    private   $status     = null;
    private   $status_fatura = null;
    private   $id_fatura = null;
	private   $metodo_pagamento = null;

    public function getUsuario(){ return $this->usuario; }
    public function setUsuario($i){ $this->usuario = $i; }
					
    public function getFatura(){ return $this->fatura; }
    public function setFatura($i){ $this->fatura = $i; }

    public function getData(){ return $this->data; }
    public function setData($i){ $this->data = $i; }

    public function getVencimento(){ return $this->vencimento; }
    public function setVencimento($i){ $this->vencimento = $i; }

    public function getPagamento(){ return $this->pagamento; }
    public function setPagamento($i){ $this->pagamento = $i; }

    public function getPeriodo(){ return $this->periodo; }
    public function setPeriodo($i){ $this->periodo = $i; }

    public function getValor(){ return $this->valor; }
    public function setValor($i){ $this->valor = $i; }

    public function getForma(){ return $this->forma; }
    public function setForma($i){ $this->forma = $i; }

    public function getTipo(){ return $this->tipo; }
    public function setTipo($i){ $this->tipo = $i; }

    public function getStatus(){ return $this->status; }
    public function setStatus($i){ $this->status = $i; }

    public function getStatusFatura(){ return $this->status_fatura; }
    public function setStatusFatura($i){ $this->status_fatura = $i; }
    
	public function getIdFatura(){ return $this->id_fatura; }
    public function setIdFatura($i){ $this->id_fatura = $i; }
	
    public function getMetodo_pagamento()
    {
        return $this->metodo_pagamento;
    }

    public function setMetodo_pagamento($metodo_pagamento)
    {
        $this->metodo_pagamento = $metodo_pagamento;
    }
	
	protected function _insert()
    {}
	
    public function Criar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,data,data_pagamento,data_vencimento,periodo,valor,forma_pagamento,tipo,status,status_fatura,id_fatura) Values (:id,:data,:pagamento,:vencimento,:periodo,:valor,:metodo,:tipo,:status,:status_fatura,:id_fatura)');
        
        $stm->bindValue(':id',$this->getUsuario());
        $stm->bindValue(':data',$this->getData());
        $stm->bindValue(':vencimento',$this->getVencimento());
        $stm->bindValue(':pagamento','');
        $stm->bindValue(':periodo',30);
        $stm->bindValue(':valor',$this->getValor());
        $stm->bindValue(':metodo',$this->getMetodo_pagamento());
        $stm->bindValue(':tipo',$this->getTipo());
        $stm->bindValue(':status',$this->getStatus());
        $stm->bindValue(':status_fatura',$this->getStatusFatura());
        $stm->bindValue(':id_fatura','');
        
        $stm->execute();
    }

    protected function _update(){}

	public function Alterar()
	{
		$db  = $this->getDb();
		$stm = $db->prepare(' update '.$this->_table.' set data=:data,data_vencimento=:data_vencimento,periodo=:periodo,valor=:valor,forma_pagamento=:forma_pagamento,tipo=:tipo,status=:status,status_fatura=:status_fatura where id=:id');
		$stm->bindValue(':id',$this->getId());
        $stm->bindValue(':data',$this->getData());
        $stm->bindValue(':data_vencimento',$this->getVencimento());
        $stm->bindValue(':periodo','30');
        $stm->bindValue(':valor',$this->getValor());
        $stm->bindValue(':forma_pagamento',$this->getForma());
        $stm->bindValue(':tipo',$this->getTipo());
        $stm->bindValue(':status',$this->getStatus());
        $stm->bindValue(':status_fatura',$this->getStatusFatura());

		$stm->execute();
	}
	
	public function getWhere($where)
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table ".$where);

        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC); 
        return $result;
    }

    public function upMetodo()
    {
         $db  = $this->getDb();
         $stm = $db->prepare(' update '.$this->_table.' set forma_pagamento=:metodo where id=:id');
         $stm->bindValue(':id',$this->getUsuario());
         $stm->bindValue(':metodo',$this->getMetodo_pagamento());
         $stm->execute();
    }
	
	public function altValor()
	{
         $db  = $this->getDb();
         $stm = $db->prepare(' update `planos` set valor=:valor where id_usuario=:id');
         $stm->bindValue(':id',$this->getUsuario());
         $stm->bindValue(':valor',$this->getValor());
         $stm->execute();
	}
	
	public function listUlFatura()
	{
			$db  = $this->getDb();
			$stm = $db->prepare("select * from  $this->_table where id_usuario=:id_usuario ORDER BY id DESC");
	
			$stm->bindValue(':id_usuario',$this->getUsuario());
			$stm->execute();
			$result = $stm->fetch(PDO::FETCH_ASSOC);    
			return $result;
	}

	public function listfat($id = null)
	{
		if(isset($id))
        {
			$db  = $this->getDb();
			$stm = $db->prepare("select * from  $this->_table where id=:id order by id desc LIMIT 1");
	
			$stm->bindValue(':id',$id);
			$stm->execute();
			$result = $stm->fetch(PDO::FETCH_ASSOC);    
			return $result;
		}
		
		if(strlen($this->getUsuario()) >= 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare("select * from  $this->_table where id_usuario=:id_usuario  order by id desc LIMIT 1");
	
			$stm->bindValue(':id_usuario',$this->getUsuario());
			$stm->execute();
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);    
			#return $result;
			
			if(count($result) == 0)
			{	
				$db  = $this->getDb();
				$stm = $db->prepare("select * from `planos` where id_usuario=:id_usuario");
		
				$stm->bindValue(':id_usuario',$this->getUsuario());
				$stm->execute();
				$res = $stm->fetch(PDO::FETCH_ASSOC);  
				
				
				#diferenca entre dias
				$data_inicial = $res['contratacao'];
				$data_final   = $res['vencimento'];
				$time_inicial = strtotime($data_inicial);
				$time_final   = strtotime($data_final);
				$diferenca    = $time_final - $time_inicial;
				$dias         = (int)floor( $diferenca / (60 * 60 * 24));
				#diferenca entre dias				
				
				$db  = $this->getDb();
				$stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,data,data_pagamento,data_vencimento,periodo,valor,forma_pagamento,tipo,status,status_fatura,id_fatura) Values (:id,:data,:pagamento,:vencimento,:periodo,:valor,:metodo,:tipo,:status,:status_fatura,:id_fatura)');
				
				$stm->bindValue(':id',$this->getUsuario());
				$stm->bindValue(':data',date("Y-m-d"));
				$stm->bindValue(':vencimento',$res['vencimento']);
				$stm->bindValue(':pagamento','');
				$stm->bindValue(':periodo',30);
				$stm->bindValue(':valor',$res['valor']);
				$stm->bindValue(':metodo',"1");
				$stm->bindValue(':tipo',"1");
				$stm->bindValue(':status',"0");
				$stm->bindValue(':status_fatura',"0");
				$stm->bindValue(':id_fatura','');
				$stm->execute();
				
				
				$db  = $this->getDb();
				$stm = $db->prepare("select * from `fatura` where id_usuario=:id_usuario");
		
				$stm->bindValue(':id_usuario',$this->getUsuario());
				$stm->execute();
				$res = $stm->fetchAll(PDO::FETCH_ASSOC);  
				return $res;
			}
			else
			{
				return $result;
			}
		}
	}
	public function getRes($id = null)
    {
		if(isset($id))
        {
			$db  = $this->getDb();
			$stm = $db->prepare("select * from  $this->_table where id=:id");
	
			$stm->bindValue(':id',$id);
			$stm->execute();
			$result = $stm->fetch(PDO::FETCH_ASSOC);    
			return $result;
		}
		
		if(strlen($this->getUsuario()) >= 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare("select * from  $this->_table where id_usuario=:id_usuario");
	
			$stm->bindValue(':id_usuario',$this->getUsuario());
			$stm->execute();
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);    
			#return $result;
			
			if(count($result) == 0)
			{	
				$db  = $this->getDb();
				$stm = $db->prepare("select * from `planos` where id_usuario=:id_usuario");
		
				$stm->bindValue(':id_usuario',$this->getUsuario());
				$stm->execute();
				$res = $stm->fetch(PDO::FETCH_ASSOC);  
				
				
				#diferenca entre dias
				$data_inicial = $res['contratacao'];
				$data_final   = $res['vencimento'];
				$time_inicial = strtotime($data_inicial);
				$time_final   = strtotime($data_final);
				$diferenca    = $time_final - $time_inicial;
				$dias         = (int)floor( $diferenca / (60 * 60 * 24));
				#diferenca entre dias				
				
				$db  = $this->getDb();
				$stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,data,data_pagamento,data_vencimento,periodo,valor,forma_pagamento,tipo,status,status_fatura,id_fatura) Values (:id,:data,:pagamento,:vencimento,:periodo,:valor,:metodo,:tipo,:status,:status_fatura,:id_fatura)');
				
				$stm->bindValue(':id',$this->getUsuario());
				$stm->bindValue(':data',date("Y-m-d"));
				$stm->bindValue(':vencimento',$res['vencimento']);
				$stm->bindValue(':pagamento','');
				$stm->bindValue(':periodo','30');
				$stm->bindValue(':valor',$res['valor']);
				$stm->bindValue(':metodo',"1");
				$stm->bindValue(':tipo',"1");
				$stm->bindValue(':status',"0");
				$stm->bindValue(':status_fatura',"1");
				$stm->bindValue(':id_fatura','');
				$stm->execute();
				
				
				$db  = $this->getDb();
				$stm = $db->prepare("select * from `fatura` where id_usuario=:id_usuario");
		
				$stm->bindValue(':id_usuario',$this->getUsuario());
				$stm->execute();
				$res = $stm->fetchAll(PDO::FETCH_ASSOC);  
				return $res;
			}
			else
			{
				return $result;
			}
			#echo count($result);
			#return $result;
			
			
		}
    }
}