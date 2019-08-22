<?php

class Sistema_Planos extends Sistema_Db_Abstract
{
    protected $_table       = 'planos';
    private   $id_user      = null;
    private   $limite       = null;
    private   $usado        = null;
    private   $contratacao  = null;
    private   $vencimento   = null;
    private   $valor        = null;
    private   $status       = null;    
    
    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        $this->limite = $limite;
    }

    public function getUsado()
    {
        return $this->usado;
    }

    public function setUsado($usado)
    {
        $this->usado = $usado;
    }

    public function getContratacao()
    {
        return $this->contratacao;
    }

    public function setContratacao($contratacao)
    {
        $this->contratacao = $contratacao;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
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
        
    }
    
    public function getRes($id=null)
    {

        $db  = $this->getDb();
        if(isset($id))
        {
            $stm = $db->prepare("select * from $this->_table where id_usuario=:id");
            $stm->bindValue(':id', $id);
        }
        else
        {
            $stm = $db->prepare("select * from $this->_table where id=:id");
            $stm->bindValue(':usuario', $this->getId());
        }
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
    
    public function ListaInfo()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from $this->_table where id_usuario=:id");
        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
    
    public function CountPontos()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update ' . $this->_table . ' set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        return $stm->execute();
    }

    public function AddPontos($pontos,$id,$idd)
    {
        $db = $this->getDb();
        $stm = $db->prepare('update ' . $this->_table . ' set limite=(limite+'.$pontos.') where id_usuario=:id');

        $stm->bindValue(':id', $id);
        $stm->execute();
		$this->CadLog($idd,$pontos);
    }

    public function editponts($limite,$usado,$id)
    {
        $db = $this->getDb();
        $stm = $db->prepare('update ' . $this->_table . ' set limite=:limite,usado=:usado where id_usuario=:id');

        $stm->bindValue(':limite', $limite);
        $stm->bindValue(':usado', $usado);
        $stm->bindValue(':id', $id);
        $stm->execute();
    }
	
    public function CadLog($usuario,$limite)
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `fat_serasa` (usuario,limite,contratacao) Values (:usuario,:limite,:contratacao)');
		
        $stm->bindValue(':usuario', $usuario);
        $stm->bindValue(':limite', $limite);
        $stm->bindValue(':contratacao',date("Y-m-d"));
        return $stm->execute();
    }
    
    public function Cadastrar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,limite,usado,contratacao,vencimento,valor,status) Values (:id,:limite,:usado,:contratacao,:vencimento,:valor,:status)');
		
        $stm->bindValue(':id',         $this->getId());
        $stm->bindValue(':limite',     $this->getLimite());
        $stm->bindValue(':usado',      $this->getUsado());
        $stm->bindValue(':contratacao',$this->getContratacao());
        $stm->bindValue(':vencimento', $this->getVencimento());
        $stm->bindValue(':valor',      $this->getValor());
        $stm->bindValue(':status', 1);
        return $stm->execute();
    }
    
    public function Alterar()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update ' . $this->_table . ' set limite=:limite, usado=:usado, contratacao=:contratacao, vencimento=:vencimento,valor=:valor, status=:status where id_usuario=:id');

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado', $this->getUsado());
        $stm->bindValue(':contratacao', $this->getContratacao());
        $stm->bindValue(':vencimento', $this->getVencimento());
        $stm->bindValue(':valor', $this->getValor());
        $stm->bindValue(':status', $this->getStatus());

        return $stm->execute();
    }
	
	public function edit()
	{
        $db = $this->getDb();
        $stm = $db->prepare('update ' . $this->_table . ' set contratacao=:contratacao, vencimento=:vencimento where id_usuario=:id');

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':contratacao', $this->getContratacao());
        $stm->bindValue(':vencimento', $this->getVencimento());
        return $stm->execute();
	}
    
    public function delete()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from '.$this->_table.' where id_usuario=:id');
        $stm->bindValue(':id',$this->getId());
        return $stm->execute();
    }
	
	public function LimparPontos($id)
	{
        $db = $this->getDb();
        $stm = $db->prepare('update `planos` set usado=:usado where id_usuario=:id');
        $stm->bindValue(':id', $id);
        $stm->bindValue(':usado', 0);
        $stm->execute();
	}
	
	public function clearFull($id)
	{
        $db = $this->getDb();
        $stm = $db->prepare('update `control_fbusk` set usado=:usado where id_usuario=:id');
        $stm->bindValue(':id', $id);
        $stm->bindValue(':usado', 0);
        $stm->execute();
		
        $db = $this->getDb();
        $stm = $db->prepare('update `control_rg` set usado=:usado where id_usuario=:id');
        $stm->bindValue(':id', $id);
        $stm->bindValue(':usado', 0);
        $stm->execute();
		
        $db = $this->getDb();
        $stm = $db->prepare('update `control_spc` set usado=:usado where id_usuario=:id');
        $stm->bindValue(':id', $id);
        $stm->bindValue(':usado', 0);
        $stm->execute();
		
        $db = $this->getDb();
        $stm = $db->prepare('update `planos` set usado=:usado where id_usuario=:id');
        $stm->bindValue(':id', $id);
        $stm->bindValue(':usado', 0);
        $stm->execute();
	}
    
    public function VerificaData()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where id_usuario=:id");

        $stm->bindValue(':id', $this->getId_user());
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);
        
        $exp_date    = $res['vencimento'];
        $todays_date = date("Y-m-d");
        $today       = strtotime($todays_date);
        $expiration_date = strtotime($exp_date);

        if ($expiration_date <= $today)
        {
            $db = $this->getDb();
            $stm = $db->prepare('update `usuarios` set status=:status where id=:id');

            $stm->bindValue(':id', $this->getId_user());
            $stm->bindValue(':status', "4"); # muda pra expirado
            return $stm->execute();
        }
    }
}