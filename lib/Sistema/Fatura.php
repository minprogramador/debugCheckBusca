<?php

class Sistema_Fatura extends Sistema_Db_Abstract
{
    protected $_table           = 'fatura';
    private   $usuairo          = null;   
    private   $data             = null;
    private   $data_vencimento  = null;
    private   $data_pagamento   = null;
    private   $periodo          = null;
    private   $valor            = null;
    private   $metodo_pagamento = null;
    private   $tipo             = null;
    private   $status           = null;
    private   $idFatura         = null;
    
    public function getUsuairo()
    {
        return $this->usuairo;
    }

    public function setUsuairo($usuairo)
    {
        $this->usuairo = $usuairo;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData_vencimento()
    {
        return $this->data_vencimento;
    }

    public function setData_vencimento($data_vencimento)
    {
        $this->data_vencimento = $data_vencimento;
    }

    public function getData_pagamento()
    {
        return $this->data_pagamento;
    }

    public function setData_pagamento($data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }

    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getMetodo_pagamento()
    {
        return $this->metodo_pagamento;
    }

    public function setMetodo_pagamento($metodo_pagamento)
    {
        $this->metodo_pagamento = $metodo_pagamento;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getidFatura()
    {
        return $this->idFatura;
    }

    public function setidFatura($id)
    {
        $this->idFatura = $id;
    }
    
    public function _insert()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,data,data_vencimento,data_pagamento,periodo,valor,metodo_pagamento,tipo,status,id_fatura) Values (:id,:data,:vencimento,:pagamento,:periodo,:valor,:metodo,:tipo,:status,:id_fatura)');
        
        $stm->bindValue(':id',$this->getUsuairo());
        $stm->bindValue(':data',$this->getData());
        $stm->bindValue(':vencimento',$this->getData_vencimento());
        $stm->bindValue(':pagamento',$this->getData_pagamento());
        $stm->bindValue(':periodo','30');
        $stm->bindValue(':valor',$this->getValor());
        $stm->bindValue(':metodo',$this->getMetodo_pagamento());
        $stm->bindValue(':tipo',$this->getTipo());
        $stm->bindValue(':status',$this->getStatus());
        $stm->bindValue(':id_fatura','');
        
        $stm->execute();
    }
    
    public function _update()        
    {
        // update
    }
    
    public function getRes()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where id_usuario=:id AND data=:data AND periodo=:periodo AND status=:status");

        $stm->bindValue(':id',$this->getUsuairo());
        $stm->bindValue(':data',$this->getData());
        $stm->bindValue(':periodo',$this->getPeriodo());
        $stm->bindValue(':status',$this->getStatus());
        
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
    
    public function upMetodo()
    {
         $db  = $this->getDb();
         $stm = $db->prepare(' update '.$this->_table.' set forma_pagamento=:metodo where id=:id');
         $stm->bindValue(':id',$this->getId());
         $stm->bindValue(':metodo',$this->getMetodo_pagamento());
         $stm->execute();
    }
	
	public function getDadosUser()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from $this->_table where id=:id");

        $stm->bindValue(':id',$this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        $res = $result;

        $db  = $this->getDb();
        $stm = $db->prepare("select * from `usuarios` where id=:id");

        $stm->bindValue(':id',$res['id_usuario']);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        $res = $result;
		return $res;
	}
	
	public function upStatus()
    {
		$db  = $this->getDb();
		if(strlen($this->getData_pagamento()) > 3)
		{
			$set = "data_pagamento=:data, status=:status";
		}
		else
		{
			$set = "status=:status";
		}
		
		$stm = $db->prepare(' update `fatura` set '.$set.', id_fatura=:id_fatura where id=:id');
		$stm->bindValue(':id',$this->getId());
		$stm->bindValue(':id_fatura',$this->getidFatura());
		$stm->bindValue(':status',$this->getStatus());
		$stm->bindValue(':data',date( "Y-m-d H:i:s"));
		$stm->execute();
		
		if($this->getStatus() == "1")
		{
			$this->AtivarCad();
		}
    }
	
	public function AtivarCad()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `fatura` where id=:id order by id desc LIMIT 1");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
		#if($res['status_fatura'] == 1)
		#{
		#	echo "fatura ja foi ativa";
		#	die;
		#}
		#else
		#{
			$db1  = $this->getDb();
			$stm1 = $db1->prepare("select * from `usuarios` where id=:id");
	
			$stm1->bindValue(':id', $res['id_usuario']);
			$stm1->execute();
			$res = $stm1->fetch(PDO::FETCH_ASSOC);
			
			$db2  = $this->getDb();
			$stm2 = $db2->prepare(" update `planos` set usado='0',vencimento=:vencimento,status=:status where id_usuario=:id");
			$stm2->bindValue(':vencimento',date("Y-m-d", strtotime(' +30 days')));
			$stm2->bindValue(':id',$res['id']);
			$stm2->bindValue(':status',"0");
			$stm2->execute();
			
			$db31  = $this->getDb();
			$stm31 = $db31->prepare(' update `fatura` set status_fatura=:status where id=:id');
			$stm31->bindValue(':id', $this->getId());
			$stm31->bindValue(':status',"1");
			$stm31->execute();

			$db13  = $this->getDb();
			$stm13 = $db13->prepare(' update `fatura` set status=:status where id=:id');
			$stm13->bindValue(':id', $this->getId());
			$stm13->bindValue(':status',"1");
			$stm13->execute();
	
			$db3  = $this->getDb();
			$stm3 = $db3->prepare(' update `usuarios` set status=:status where id=:id');
			$stm3->bindValue(':id',$res['id']);
			$stm3->bindValue(':status',"1");
			$stm3->execute();
	
			$db4  = $this->getDb();
			$stm4 = $db4->prepare(" update `control_spc` set usado='0',status=:status where id_usuario=:id");
	
			$stm4->bindValue(':id',$res['id']);
			$stm4->bindValue(':status',"1");
			$stm4->execute();
	
			$db5  = $this->getDb();
			$stm5 = $db5->prepare(" update `control_rg` set usado='0',status=:status where id_usuario=:id");
	
			$stm5->bindValue(':id',$res['id']);
			$stm5->bindValue(':status',"1");
			$stm5->execute();
	
			$db6  = $this->getDb();
			$stm6 = $db6->prepare(" update `control_fbusk` set usado='0',status=:status where id_usuario=:id");
	
			$stm6->bindValue(':id',$res['id']);
			$stm6->bindValue(':status',"1");
			$stm6->execute();

			$db7  = $this->getDb();
			$stm7 = $db7->prepare(" update `control_inss` set usado='0',status=:status where id_usuario=:id");
	
			$stm7->bindValue(':id',$res['id']);
			$stm7->bindValue(':status',"1");
			$stm7->execute();

			$db8  = $this->getDb();
			$stm8 = $db8->prepare(" update `control_confirme` set usado='0',status=:status where id_usuario=:id");
	
			$stm8->bindValue(':id',$res['id']);
			$stm8->bindValue(':status',"1");
			$stm8->execute();

			$db9  = $this->getDb();
			$stm9 = $db9->prepare(" update `control_pai` set usado='0',status=:status where id_usuario=:id");
	
			$stm9->bindValue(':id',$res['id']);
			$stm9->bindValue(':status',"1");
			$stm9->execute();

            $db9  = $this->getDb();
            $stm9 = $db9->prepare(" update `control_cnpj1` set usado='0',status=:status where id_usuario=:id");
    
            $stm9->bindValue(':id',$res['id']);
            $stm9->bindValue(':status',"1");
            $stm9->execute();

            $db9  = $this->getDb();
            $stm9 = $db9->prepare(" update `control_cpf1` set usado='0',status=:status where id_usuario=:id");
    
            $stm9->bindValue(':id',$res['id']);
            $stm9->bindValue(':status',"1");
            $stm9->execute();

            $db9  = $this->getDb();
            $stm9 = $db9->prepare(" update `control_cpf1` set usado='0',status=:status where id_usuario=:id");
    
            $stm9->bindValue(':id',$res['id']);
            $stm9->bindValue(':status',"1");
            $stm9->execute();

            $db9  = $this->getDb();
            $stm9 = $db9->prepare(" update `control_sec` set usado='0',status=:status where id_usuario=:id");
    
            $stm9->bindValue(':id',$res['id']);
            $stm9->bindValue(':status',"1");
            $stm9->execute();

            $db9  = $this->getDb();
            $stm9 = $db9->prepare(" update `control_seekloc` set usado='0',status=:status where id_usuario=:id");
    
            $stm9->bindValue(':id',$res['id']);
            $stm9->bindValue(':status',"1");
            $stm9->execute();                        

            $db10  = $this->getDb();
            $stm10 = $db10->prepare(" update `control_onebusca` set usado='0',status=:status where id_usuario=:id");
    
            $stm10->bindValue(':id',$res['id']);
            $stm10->bindValue(':status',"1");
            $stm10->execute();       

            $db11  = $this->getDb();
            $stm11 = $db11->prepare(" update `control_cpf_1` set usado='0',status=:status where id_usuario=:id");
    
            $stm11->bindValue(':id',$res['id']);
            $stm11->bindValue(':status',"1");
            $stm11->execute();  

		#}
	}
	
    public function getDados()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where id_usuario=:id");

        $stm->bindValue(':id', $this->getUsuairo());
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
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
	
    public function getContas($conta=null)
    {
        $db  = $this->getDb();
		if(isset($conta))
		{
			$stm = $db->prepare("select * from `forma_pagamento` where nome='$conta' and status=:status");
			$stm->bindValue(':status', 2);
			$stm->execute();
			$result = $stm->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$stm = $db->prepare("select * from `forma_pagamento` where status=:status");
			$stm->bindValue(':status', 2);
			$stm->execute();
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		}

        return $result;
    }
	
	public function cadPagamento($id,$valor,$ndoc,$data_pg,$ncontrole=null,$imagem=null,$obs=null,$status)
	{
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `pagamentos` (id_usuario,valor,ndoc,data_pg,ncontrole,imagem,obs,status) Values (:id,:valor,:ndoc,:data_pg,:ncontrole,:imagem,:obs,:status)');
        
        $stm->bindValue(':id',$id);
        $stm->bindValue(':valor',$valor);
        $stm->bindValue(':ndoc',$ndoc);
        $stm->bindValue(':data_pg',$data_pg);
        $stm->bindValue(':ncontrole',$ncontrole);
        $stm->bindValue(':imagem',$imagem);
        $stm->bindValue(':obs',$obs);
        $stm->bindValue(':status',$status);
        
        $stm->execute();
	}
	
	public function VerificPagamento($id)
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `pagamentos`  where `id_usuario`='$id' and status='0'");

        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC); 
        return $result;
	}
}