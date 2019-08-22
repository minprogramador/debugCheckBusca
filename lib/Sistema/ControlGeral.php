<?php

class Sistema_ControlGeral extends Sistema_Db_Abstract
{
    protected $_table  = 'control_geral';
    private   $idUser  = null;
    private   $servico = null;
    private   $limite  = null;
    private   $usado   = null;
    private   $status  = null;
    
    public function getIdUser()         { return $this->idUser;      }
    public function setIdUser($user)    { $this->idUser = $user;     }
    public function getServico()        { return $this->servico;     }
    public function setServico($servico){ $this->servico = $servico; }
    public function getLimite()         { return $this->limite;      }
    public function setLimite($limite)  { $this->limite = $limite;   }
    public function getUsado()          { return $this->limite;      }
    public function setUsado($usado)    { $this->usado = $usado;     }
    public function getStatus()         { return $this->status;      }
    public function setStatus($status)  { $this->status = $status;   }

    protected function _insert()
    {
        // insert
    }
    
    protected function _update()
    {
        //update
    }
    
    public function Permissao()
    {
        $usuario = new Sistema_Usuarios();
        $usuario->setUsuario($_SESSION['getUsuario']);
        $res = $usuario->getRes();
        
        $re  = $res['servicos'];
        $re  = explode(',',$re);

        foreach($re as $res)
        {
            if($res == $this->getServico())
            {
                return true;
            }
        }
    }

    function getLimites()
    {
        $Config  = new Sistema_Configuracao();
        $limite = '100';
        if($this->getServico() == 'buscaGold')
        {
            $limite = '50';
        }
        if($this->getServico() == 'Natt2')
        {
            $limite = '100';
        }

        $db1    = $this->getDb();
        $stm1   = $db1->prepare("select * from ".$this->_table." where id_usuario=:id and servico=:servico");
        $stm1->bindValue(':id',      $this->getIdUser());
        $stm1->bindValue(':servico', $this->getServico());
        $stm1->execute();
        $result = $stm1->fetch(PDO::FETCH_ASSOC);

        if(count($result) == '1')
        {
            $db  = $this->getDb();
            $stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,servico,limite,usado,status) Values (:id,:servico,:limite,:usado,:status)');
            $stm->bindValue(':id', $this->getIdUser());
            $stm->bindValue(':servico', $this->getServico());

            if(strlen($this->getLimite()) > 0){ $stm->bindValue(':limite', $this->getLimite()); }else{ $stm->bindValue(':limite', $limite); }
            if(strlen($this->getUsado()) > 0) { $stm->bindValue(':usado', $this->getUsado()); }else{ $stm->bindValue(':usado', "0");}

            $stm->bindValue(':status', "1");
            $stm->execute();

            return array('limite'=>$limite,'usado'=>'0','status'=>'1');
        }
        else
        {
            return array('limite'=>$result['limite'],'usado'=>$result['usado'],'status'=>$result['status']);
        }
    }

    function saveConsulta()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update '.$this->_table.' set usado=(usado+1) where id_usuario=:id and servico=:servico');
        $stm->bindValue(':id', $this->getIdUser());
        $stm->bindValue(':servico', $this->getServico());
        $stm->execute();
    }

    public function editService($limite,$usado,$id,$servico)
    {
        $db = $this->getDb();
        $stm = $db->prepare('update '.$this->_table.' SET limite=:limite,usado=:usado where id_usuario=:id and servico=:servico');
        $stm->bindValue(':limite',  $limite);
        $stm->bindValue(':usado',   $usado);
        $stm->bindValue(':id',      $id);
        $stm->bindValue(':servico', $servico);
        $stm->execute();
    }

    public function saveService($limite,$usado,$id,$servico)
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into '.$this->_table.' (id_usuario,servico,limite,usado,status) Values (:id,:servico,:limite,:usado,:status)');
        $stm->bindValue(':id',      $id);
        $stm->bindValue(':servico', $servico);
        $stm->bindValue(':limite',  $limite);
        $stm->bindValue(':usado',   $usado);
        $stm->bindValue(':status',  '1');
        $stm->execute();
    }

}