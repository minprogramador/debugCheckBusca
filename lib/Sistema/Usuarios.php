<?php

class Sistema_Usuarios extends Sistema_Db_Abstract
{
    protected $_table   = 'usuarios';
    private   $nome     = null;
    private   $email    = null;
    private   $usuario  = null;
    private   $senha    = null;
    private   $servicos = null;
    private   $serasa   = null;
    private   $captcha  = null;
	private   $acessos  = null;
    private   $status   = null;
    
    public function getCaptcha()
    {
        return $this->captcha;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
    }
        
    public function getSerasa()
    {
        return $this->serasa;
    }

    public function setSerasa($serasa)
    {
        $this->serasa = $serasa;
    }
        
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

	public function setEmail($email) 
    {
        $this->email = $email;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getServicos()
    {
        return $this->servicos;
    }

    public function setServicos($servicos)
    {
        $this->servicos = $servicos;
    }
	
	public function getAcessos()
	{
		return $this->acessos;
	}

	public function setAcessos($acesso)
	{
		$this->acessos = $acesso;
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

    protected function _update() {}

    public function getRes($id=null)
    {
        $db  = $this->getDb();
        if(isset($id))
        {
            $stm = $db->prepare("select * from $this->_table where id=:id");
            $stm->bindValue(':id', $id);
        }
        else
        {
            $stm = $db->prepare("select * from $this->_table where usuario=:usuario");
            $stm->bindValue(':usuario', $this->getUsuario());
        }
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
    
    public function busca($usuario)
    {
        if(isset($usuario))
        {
            $db1  = $this->getDb();
            $stm1 = $db1->prepare("select * from $this->_table where usuario=:usuario");
            $stm1->bindValue(':usuario', $usuario);
            $stm1->execute();
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);    
            $id = $result1['id'];
        }
        
        $db  = $this->getDb();
        $stm = $db->prepare("select `usuarios`.*, `planos`.contratacao,`planos`.vencimento,`planos`.limite,`planos`.usado,`planos`.valor FROM `usuarios` INNER JOIN `planos` ON `usuarios`.`id` = `planos`.`id_usuario` WHERE (id_usuario=:id AND usuarios.id = usuarios.id)");
       
        if(isset($id))
        {
            $stm->bindValue(':id',$id);
        }
        else
        {
            $stm->bindValue(':id',$this->getId());
        }
        
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTudo()
    {
        $db  = $this->getDb();
        
        $stm = $db->prepare("select `usuarios`.*, `planos`.contratacao,`planos`.vencimento,`planos`.limite,`planos`.usado,`planos`.valor FROM `usuarios` INNER JOIN `planos` ON `usuarios`.`id` = `planos`.`id_usuario` WHERE (id_usuario=:id AND usuarios.id = usuarios.id)");
        $stm->bindValue(':id',$this->getId());
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function Logon()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where usuario=:usuario");

        $stm->bindValue(':usuario', $this->getUsuario());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);

        $_SESSION['getDados']   = true;
        $_SESSION['getId']      = $result['id'];
        $_SESSION['getNome']    = $result['nome'];
        $_SESSION['getEmail']   = $result['email'];
        $_SESSION['getUsuario'] = $result['usuario'];
        $_SESSION['getSerasa']  = $result['serasa'];
        $_SESSION['getStatus']  = $result['status'];   
    }
    
    public function Cadastrar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `usuarios` (nome,email,usuario,senha,servicos,serasa,captcha,acessos,status) Values (:nome,:email,:usuario,:senha,:servicos,:serasa,:captcha,:acessos,:status)');
		
        $stm->bindValue(':nome',    $this->getNome());
        $stm->bindValue(':email',   $this->getEmail());
        $stm->bindValue(':usuario', $this->getUsuario());
        $stm->bindValue(':senha',   md5($this->getSenha()));
        $stm->bindValue(':servicos',$this->getServicos());
        $stm->bindValue(':serasa',  $this->getSerasa());
        $stm->bindValue(':captcha',  $this->getCaptcha());
        $stm->bindValue(':acessos',  $this->getAcessos());
        $stm->bindValue(':status',  $this->getStatus());
        return $stm->execute();
    }
    
    public function Alterar()
    {
        $db = $this->getDb();      

        if(strlen($this->getSenha()) > 3)
        {
            $stm = $db->prepare('UPDATE ' . $this->_table . ' SET  nome= :nome, email= :email, usuario= :usuario, senha= :senha, servicos= :servicos,serasa= :serasa,captcha= :captcha, acessos= :acessos, status= :status where id=:id');
            $stm->bindValue(':senha', md5($this->getSenha()));
        }
        else
        {
            $stm = $db->prepare('UPDATE ' . $this->_table . ' SET  nome= :nome, email= :email, usuario= :usuario, servicos= :servicos,serasa= :serasa,captcha= :captcha, acessos= :acessos, status= :status where id=:id');
        }     

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':nome', $this->getUsuario());
        $stm->bindValue(':email', $this->getEmail());
        $stm->bindValue(':usuario', $this->getUsuario());
        $stm->bindValue(':servicos', $this->getServicos());
        $stm->bindValue(':serasa', $this->getSerasa());
        $stm->bindValue(':captcha',  $this->getCaptcha());
        $stm->bindValue(':acessos',  $this->getAcessos());
        $stm->bindValue(':status', $this->getStatus());
        return $stm->execute();   
    } 
	
	public function mudaStatus()
	{
        $db = $this->getDb();      

		$stm = $db->prepare('UPDATE ' . $this->_table . ' SET  status= :status where id=:id');

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':status', 4);
        $stm->execute();   
	}

    public function verEmail()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where email=:email");

        $stm->bindValue(':email', $this->getEmail());
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);    
        return $result;
    }  

    public function verUsuario()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from 
               $this->_table where usuario=:usuario");

        $stm->bindValue(':usuario', $this->getUsuario());
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);    
        return $result;
    }

    // log concentre
    public function LogCadCons($usuario,$doc,$ip,$referer)
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `concentre_control` (usuario,doc,data,ip,referer) Values (:usuario,:doc,:data,:ip,:referer)');
        
        $stm->bindValue(':usuario',  $usuario);
        $stm->bindValue(':doc',      $doc);
        $stm->bindValue(':data',     date("Y-m-d H:i:s"));
        $stm->bindValue(':ip',       $ip);
        $stm->bindValue(':referer',  $referer);
        return $stm->execute();
    }

    public function LogVerific()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `concentre_control` order by id DESC LIMIT 1");
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}