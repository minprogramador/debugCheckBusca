<?php



class Sistema_Servicos extends Sistema_Db_Abstract

{

    protected $_table  = 'servicos';

    private   $servico = null;

    private   $imagem  = null;

    private   $status  = null;

    

    public function getServico()

    {

        return $this->servico;

    }



    public function setServico($servico)

    {

        $this->servico = $servico;

    }



    public function getImagem()

    {

        return $this->imagem;

    }



    public function setImagem($imagem)

    {

        $this->imagem = $imagem;

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

    

    public function Permissao()

    {

        if(LOGON == 2)

        {

            return true;

            die;

        }

        

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

                die;

            }

        }

    }

    

    public function getTudo()

    {

        $db  = $this->getDb();

        $stm = $db->prepare("select servico from ".$this->_table." ORDER BY id");



        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_COLUMN);

        $a = "";

        

        foreach ($result as $v)

        {

            $a .= $v.',';

        }

        

        return $a;

    }


    public function getDadoss($servico)
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `senhas` where servico=:servico");
		$stm->bindValue(':servico',$servico);

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    

    public function getlist()

    {

        $db  = $this->getDb();

        $stm = $db->prepare("select servico from ".$this->_table." ORDER BY id");



        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_COLUMN);

        

        $usuario = new Sistema_Usuarios();

        $res = $usuario->getRes($this->getId());



        $re  =  $res['servicos'];

        $re  = explode(',',$re);

            

        $a = array();

        foreach ($result as $v)

        {

            $check = "";

            if (in_array($v,$re))

            {

                $check = "checked='checked'";

            }

            

            $a[] = "<input name='servico[]' $check type='checkbox' value='$v'/>$v";

        }

        return $a;

    }
	
	public function PermissaoConfirme()
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
				$servico = true;
            }
        }
		
			if($servico == 1)
			{	
			   $db1  = $this->getDb();
			   $stm1 = $db1->prepare("select * from `control_confirme` where id_usuario=:id");
		
			   $stm1->bindValue(':id', $_SESSION['getId']);
			   $stm1->execute();
				$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($result1) > 0)
				{
					
					if(strlen($result1['limite']) == 0)
					{
						$limite = $result1[0]['limite'];
						$usado  = $result1[0]['usado'];
					}
					else
					{
						$limite = $result1['limite'];
						$usado  = $result1['usado'];
					}
			
					if($usado > $limite)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$db1  = $this->getDb();
					$stm1 = $db1->prepare("select * from `control_confirme` where id_usuario=:id");
			
					$stm1->bindValue(':id', $_SESSION['getId']);
					$stm1->execute();
					$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
					if(count($result1) < 1)
					{
						$db  = $this->getDb();
						$stm = $db->prepare(' insert into `control_confirme` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
						$stm->bindValue(':id',     $_SESSION['getId']);
						$stm->bindValue(':limite', "400");
						$stm->bindValue(':usado',  "0");
						$stm->bindValue(':status', "1");
						$stm->execute();
						return true;
					}
				}
			}
			else
			{
				return false;
			}
		
	}
	
	public function CountPontosConfirme()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_confirme` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }

    public function pontospaii()
    {
    	$db1  = $this->getDb();
		$stm1 = $db1->prepare("select * from `control_pai` where id_usuario=:id");
		
		$stm1->bindValue(':id', $_SESSION['getId']);
		$stm1->execute();
		$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
		return $result1[0];
    }

	public function PermissaoPai()
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
				$servico = true;
            }
        }
		
			if($servico == 1)
			{	
			   $db1  = $this->getDb();
			   $stm1 = $db1->prepare("select * from `control_pai` where id_usuario=:id");
		
			   $stm1->bindValue(':id', $_SESSION['getId']);
			   $stm1->execute();
				$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($result1) > 0)
				{
					
					if(strlen($result1['limite']) == 0)
					{
						$limite = $result1[0]['limite'];
						$usado  = $result1[0]['usado'];
					}
					else
					{
						$limite = $result1['limite'];
						$usado  = $result1['usado'];
					}
			
					if($usado > $limite)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$db1  = $this->getDb();
					$stm1 = $db1->prepare("select * from `control_pai` where id_usuario=:id");
			
					$stm1->bindValue(':id', $_SESSION['getId']);
					$stm1->execute();
					$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
					if(count($result1) < 1)
					{
						$db  = $this->getDb();
						$stm = $db->prepare(' insert into `control_pai` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
						$stm->bindValue(':id',     $_SESSION['getId']);
						$stm->bindValue(':limite', "0");
						$stm->bindValue(':usado',  "0");
						$stm->bindValue(':status', "1");
						$stm->execute();
						return true;
					}
				}
			}
			else
			{
				return false;
			}

	}

public function getpontosseek()
{
	$db1  = $this->getDb();
	$stm1 = $db1->prepare("select * from `control_seekloc` where id_usuario=:id");
		
	$stm1->bindValue(':id', $_SESSION['getId']);
	$stm1->execute();
	$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
	return $result1[0];
}

	public function PermissaoSeekloc()
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
				$servico = true;
            }
        }
		
			if($servico == 1)
			{	
			   $db1  = $this->getDb();
			   $stm1 = $db1->prepare("select * from `control_seekloc` where id_usuario=:id");
		
			   $stm1->bindValue(':id', $_SESSION['getId']);
			   $stm1->execute();
				$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($result1) > 0)
				{
					
					if(strlen($result1['limite']) == 0)
					{
						$limite = $result1[0]['limite'];
						$usado  = $result1[0]['usado'];
					}
					else
					{
						$limite = $result1['limite'];
						$usado  = $result1['usado'];
					}
			
					if($usado >= $limite)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$db1  = $this->getDb();
					$stm1 = $db1->prepare("select * from `control_seekloc` where id_usuario=:id");
			
					$stm1->bindValue(':id', $_SESSION['getId']);
					$stm1->execute();
					$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
					if(count($result1) < 1)
					{
						$db  = $this->getDb();
						$stm = $db->prepare(' insert into `control_seekloc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
						$stm->bindValue(':id',     $_SESSION['getId']);
						$stm->bindValue(':limite', "0");
						$stm->bindValue(':usado',  "0");
						$stm->bindValue(':status', "1");
						$stm->execute();
						return true;
					}
				}
			}
			else
			{
				return false;
			}

	}

	public function CountPontosSeekloc()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_seekloc` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }
	
	public function lpontSeekloc()
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_seekloc` where id_usuario=:id");
	
		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_seekloc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "200");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
			
		return $result;
	}


	public function listPontosCpf1()
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_cpf_1` where id_usuario=:id");
	
		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_cpf_1` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "100");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
			
		return $result;
	}


	public function listPontosCnpj1()
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_cnpj_1` where id_usuario=:id");
	
		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_cnpj_1` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
			
		return $result;
	}
	
	public function upCpf1($limite,$usado)
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_cpf_1` where id_usuario=:id");

		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		$ver = count($result);
		
		if($ver == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_cpf_1` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		else
		{
			$db = $this->getDb();
			$stm = $db->prepare(" update `control_cpf_1` set limite=:limite, usado=:usado where id_usuario=:id");

			$stm->bindValue(':id', $this->getId());
			$stm->bindValue(':limite',$limite);
			$stm->bindValue(':usado',$usado);
			return $stm->execute();
		}
	}



	public function upCnpj1($limite,$usado)
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_cnpj_1` where id_usuario=:id");

		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		$ver = count($result);
		
		if($ver == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_cnpj_1` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		else
		{
			$db = $this->getDb();
			$stm = $db->prepare(" update `control_cnpj_1` set limite=:limite, usado=:usado where id_usuario=:id");

			$stm->bindValue(':id', $this->getId());
			$stm->bindValue(':limite',$limite);
			$stm->bindValue(':usado',$usado);
			return $stm->execute();
		}
	}

	public function CountPontosCPF1()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_cpf_1` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }

	public function CountPontosCNPJ1()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_cnpj_1` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }

	public function upSeekloc($limite,$usado)
	{
		$db  = $this->getDb();
		$stm = $db->prepare("select * from `control_seekloc` where id_usuario=:id");

		$stm->bindValue(':id', $this->getId());
		$stm->execute();
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		$ver = count($result);
		
		if($ver == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_seekloc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
				
			$stm->bindValue(':id',     $this->getId());
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		else
		{
			$db = $this->getDb();
			$stm = $db->prepare(" update `control_seekloc` set limite=:limite, usado=:usado where id_usuario=:id");

			$stm->bindValue(':id', $this->getId());
			$stm->bindValue(':limite',$limite);
			$stm->bindValue(':usado',$usado);
			return $stm->execute();
		}
	}

	
	public function CountPontosPai()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_pai` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }



	public function PermissaoInss()
	{        
       $usuario = new Sistema_Usuarios();
       $usuario->setUsuario($_SESSION['getUsuario']);
       $res = $usuario->getRes();

        $re  = $res['servicos'];
        $re  = explode(',',$re);
		 $servico = null;
		 
		 
        foreach($re as $res)
        {
			
            if($res == $this->getServico())
            {
				$servico = true;
            }
        }
		
			if($servico == 1)
			{	
			   $db1  = $this->getDb();
			   $stm1 = $db1->prepare("select * from `control_inss` where id_usuario=:id");
		
			   $stm1->bindValue(':id', $_SESSION['getId']);
			   $stm1->execute();
				$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($result1) > 0)
				{
					
					if(strlen($result1['limite']) == 0)
					{
						$limite = $result1[0]['limite'];
						$usado  = $result1[0]['usado'];
					}
					else
					{
						$limite = $result1['limite'];
						$usado  = $result1['usado'];
					}
			
					if($usado > $limite)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$db1  = $this->getDb();
					$stm1 = $db1->prepare("select * from `control_inss` where id_usuario=:id");
			
					$stm1->bindValue(':id', $_SESSION['getId']);
					$stm1->execute();
					$result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
					if(count($result1) < 1)
					{
						$db  = $this->getDb();
						$stm = $db->prepare(' insert into `control_pai` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
						
						$stm->bindValue(':id',     $_SESSION['getId']);
						$stm->bindValue(':limite', "10");
						$stm->bindValue(':usado',  "0");
						$stm->bindValue(':status', "1");
						$stm->execute();
						return true;
					}
				}
			}
			else
			{
				return false;
			}
	}
	
	public function CountPontosInss()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_inss` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }

	
	public function saveccSeekloc($cookie)
	{
		$db = $this->getDb();
		$stm = $db->prepare('update senhas set cookie=:cookie where servico=:servico');
        $stm->bindValue(':cookie', $cookie);

		$stm->bindValue(':servico', "Seekloc");
		$stm->execute();
	}
	
	public function verificSeekloc($cookie)
	{
		$util = new Sistema_Util();
		$re = $util->curl('http://200.201.193.98/seekloc/sistema.php',$cookie,null,false,'http://200.201.193.98/seekloc/sistema.php');	
		#echo $re;
		$r = $util->corta($re,'<fieldset><legend>T','</legend>');

		if($r == "elefone")
		{
			return true;
		}
		else
		{
			return false;
		}
	}




    public function PermissaoOneBusca()
    {        
        $usuario = new Sistema_Usuarios();
        $usuario->setUsuario($_SESSION['getUsuario']);
        $res = $usuario->getRes();
        $resfu = $res;
        $re  = $res['servicos'];
        $re  = explode(',',$re);
        $status = '';
        foreach($re as $res){ if($res == $this->getServico()){ $status .= 'ok'; } }
        
        $idxx = $resfu['id'];

        if($status == 'ok')
        {
            $db1    = $this->getDb();
            $stm1   = $db1->prepare("select * from `control_onebusca` where id_usuario=:id");

            $stm1->bindValue(':id', $idxx);
            $stm1->execute();
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            
            if(count($result1) == 1)
            {
                $db  = $this->getDb();
                $stm = $db->prepare(' insert into `control_onebusca` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
                    
                $stm->bindValue(':id',     $idxx);
                $stm->bindValue(':limite', "200");
                $stm->bindValue(':usado',  "0");
                $stm->bindValue(':status', "1");
                $stm->execute();
                return true;
            }
            else
            {
                if($result1['limite'] <= $result1['usado'])
                {
                    header("Location:".PATCH.'/?limite');
                    die;
                }
                else
                {
                    return true;
                }
            }
        }
    }

    public function listPontosOneBusca($idd=null)
    {

        $db  = $this->getDb();
        $stm = $db->prepare("select * from `control_onebusca` where id_usuario=:id");

        if(isset($idd))
        {
            $stm->bindValue(':id', $idd);
        }
        else
        {
            $stm->bindValue(':id', $_SESSION['getId']);
        }
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function CountPontosOneBusca()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_onebusca` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }

    public function editOneBusca($limite,$usado,$id)
    {
        $db1    = $this->getDb();
        $stm1   = $db1->prepare("select * from `control_onebusca` where id_usuario=:id");

        $stm1->bindValue(':id', $id);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC); 

        if(count($result1) == 1)
        {
            $db  = $this->getDb();
            $stm = $db->prepare(' insert into `control_onebusca` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
                    
            $stm->bindValue(':id', $id);

            if(isset($limite))
            {
                $stm->bindValue(':limite', $limite);
            }
            else
            {
                $stm->bindValue(':limite', "300");
            }
            
            if(isset($usado))
            {
                $stm->bindValue(':usado', $usado);
            }
            else
            {
                $stm->bindValue(':usado', "0");
            }
            $stm->bindValue(':status', "1");
            $stm->execute();
        }
        else
        {
            $db = $this->getDb();
            $stm = $db->prepare(" update `control_onebusca` set limite=:limite, usado=:usado where id_usuario=:id");

            $stm->bindValue(':id', $id);
            $stm->bindValue(':limite',$limite);
            $stm->bindValue(':usado',$usado);
            return $stm->execute();
        }
    }

}