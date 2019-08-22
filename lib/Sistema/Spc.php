<?php

#Criado em: 09/11/2012
#brunno duarte.
#by puttyoe@hotmail.com

class Sistema_Spc extends Sistema_Db_Abstract
{
    protected $_table  = "senhas";
	private   $limite  = null;
	private   $usado   = null;
    private   $servico = null;
	private   $tipo    = null;
    private   $usuario = null;
    private   $senha   = null;
	private   $cpf     = null;
	private   $acess   = null;
	private   $codig   = null;
	private   $contr   = null;
	private   $resultado = null;
	public    $cookie  = null;

    public function getCookie(){ return $this->cookie; }

    public function setCookie($cookie){ $this->cookie = $cookie; }

    public function getLimite(){ return $this->limite; }

    public function setLimite($limite){ $this->limite = $limite; }

    public function getUsado(){ return $this->usado; }

    public function setUsado($usado){ $this->usado = $usado; }


    public function getAcess(){ return $this->acess; }

    public function setAcess($ac){ $this->acess = $ac; }

    public function getCodig(){ return $this->codig; }

    public function setCodig($cd){ $this->codig = $cd; }
	
    public function getContr(){ return $this->contr; }

    public function setContr($cr){ $this->contr = $cr; }

    public function getResultado(){ return $this->resultado; }

    public function setResultado($res){ $this->resultado = $res; }
	
    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }
	
    public function getServico()
    {
        return $this->servico;
    }

    public function setServico($servico)
    {
        $this->servico = $servico;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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

    public function curl($url,$cookies,$post,$header=true,$referer=null,$follow=false,$proxy=false)
    {	
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
		if(isset($referer)){ curl_setopt($ch, CURLOPT_REFERER,$referer); }
		else{ curl_setopt($ch, CURLOPT_REFERER,$url); }
		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
		}
		
		#proxyyyy
	curl_setopt($ch, CURLOPT_PROXY, "104.237.194.221:60099");
	curl_setopt($ch, CURLOPT_PROXYUSERPWD, "beave1939:cLkDmXiT");

        //curl_setopt($ch, CURLOPT_PROXYPORT, "46996");
   		#proxyyy
		#curl_setopt($ch, CURLOPT_PROXY, "wertdev2.no-ip.biz:8888");
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		
		$res = curl_exec( $ch);
		curl_close($ch); 
		#return utf8_decode($res);
        return ($res);
    }

    protected function _insert()
	{
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `control_spc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
		
        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':status', 1);
        return $stm->execute();
	}

    public function Cadastrar()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' insert into `control_spc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');

        $stm->bindValue(':id',     $this->getUsuario());
        $stm->bindValue(':limite', $this->getLimite());
        $stm->bindValue(':usado',  $this->getUsado());
        $stm->bindValue(':status', '1');

        return $stm->execute();
    }
	
    protected function _update()
	{
        $db = $this->getDb();
        $stm = $db->prepare(" update `control_spc` set limite=:limite, usado=:usado where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->bindValue(':limite',$this->getLimite());
        $stm->bindValue(':usado',$this->getUsado());
        return $stm->execute();
	}
	
	public function listPontos()
	{
        $db  = $this->getDb();
        $stm = $db->prepare("select * from `control_spc` where id_usuario=:id");

        $stm->bindValue(':id', $this->getId());
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
        
    public function get_cc()
    {
        #$db  = $this->getDb();
        #$stm = $db->prepare("select * from $this->_table where servico=:servico");

        #$stm->bindValue(':servico', $this->getServico());
        #$stm->execute();
        #$result = $stm->fetch(PDO::FETCH_ASSOC);
        #$usuario = '20000231872';
        #$senha   = '0305';

		if(isset($_GET['veiculo']))
        {
        	$usuario ='20000351690';
        	$senha   = '0528';
        }
	    else
	    {
	    	$usuario = '20000824296';
        	$senha   = '1268';
        }

		return 'lk_codig='.$usuario.'&lk_senha='.$senha.'&lk_width=123&lk_consu=SENHA';

    }
    
    public function Permissao()
    {        
        $usuario = new Sistema_Usuarios();
        $usuario->setUsuario($_SESSION['getUsuario']);
        $res = $usuario->getRes();

        $result = $this->getRes($res['id']);

		if(count($result) == 1)
		{
			$db  = $this->getDb();
			$stm = $db->prepare(' insert into `control_spc` (id_usuario,limite,usado,status) Values (:id,:limite,:usado,:status)');
			
			$stm->bindValue(':id',     $res['id']);
			$stm->bindValue(':limite', "10");
			$stm->bindValue(':usado',  "0");
			$stm->bindValue(':status', "1");
			$stm->execute();
		}
		elseif($result['limite'] <= $result['usado'])
        {
            header("Location:".PATCH.'/Spc?limite');
            die;
        }
    }
	
	public function saveLog()
	{
		$db  = $this->getDb();
		$stm = $db->prepare(' insert into `logs_spc` (id_usuario,doc,servico,tipo,resultado,data) Values (:id,:doc,:servico,:tipo,:resultado,:data)');
			
		$stm->bindValue(':id',     $_SESSION['getId']);
		$stm->bindValue(':doc', $this->getCpf());
		$stm->bindValue(':servico',  $this->getServico());
		$stm->bindValue(':tipo',  $this->getTipo());
		$stm->bindValue(':resultado', $this->getResultado());
		$stm->bindValue(':data', date('Y-m-d H:i:s'));
		$stm->execute();
	}
	
	public function ultConsulta()
	{
        $db  = $this->getDb();
		
		$stm = $db->prepare("select * from `logs_spc` where id_usuario=:id ORDER BY id DESC");
        $stm->bindValue(':id', $this->getUsuario());
		
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
	}
	
    public function CountPontos()
    {
        $db = $this->getDb();
        $stm = $db->prepare('update `control_spc` set usado=(usado+1) where id_usuario=:id');

        $stm->bindValue(':id', $_SESSION['getId']);
        $stm->execute();
    }
	
    public function getRes($id=null)
    {
        $db  = $this->getDb();
        if(isset($id))
        {
            $stm = $db->prepare("select * from `control_spc` where id_usuario=:id");
            $stm->bindValue(':id', $id);
        }
        else
        {
            $stm = $db->prepare("select * from `control_spc` where id=:id");
            $stm->bindValue(':usuario', $this->getId());
        }
		
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
    }
	
	public function Veiculo($tipo)
	{
		$this->Logar();
		
		if(strlen($this->getAcess()) < 2)
		{
			return "<pre>Sistema indisponivel no momento.</pre>";
			die;
		}

		$tipo = $tipo;
		$codigo = $this->getCpf();
		$util = new Sistema_Util();

		$lk_acess = $this->getAcess();
		$lk_codig = $this->getCodig();
		$ws_contr = $this->getContr();
		$cookie   = $this->getCookie();
			
		$pp = "ws_contr=$ws_contr+&lk_codig=$lk_codig&lk_senha=$lk_codig&ws_menu=CERTOCAR&ws_fprs=N&ws_fcns=N&ws_frfs=N&ws_fgrs=N&ws_fins=N";
		$res = $this->curl('https://www.scpcnet.com.br/ACSPNET/Programas/SIAPH049.php',$cookie,$pp,'https://www.bvsnet.com.br/cgi-bin/db2www/NETPO101.mbr/menu');

		$p = "ws_contr=$ws_contr+&co_usuario=$lk_codig&co_senha=$lk_codig&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&nm_programa=MODULAR&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=901&lk_fprop=+++&lk_fcnfp=+++&lk_fdbto=+++&lk_frbft=+++&lk_fdpvt=+++&lk_fgrvm=905&lk_fintg=+++&lk_fsnst=+++&lk_fdeco=+++&lk_fprec=+++&lk_fleil=+++&lk_fmoto=+++&lk_fcrvl=+++&lk_renav=on&lk_grvma=on&lk_chass=chassi&lk_chasn=$codigo&lk_chasc=&lk_ufede=&lk_tdoct=cpf&lk_docto=&lk_crvls=";
		$res = $this->curl('https://www.scpcnet.com.br/ACSPNET/Programas/SIAPH063.php',$cookie,$pp,'https://www.bvsnet.com.br/cgi-bin/db2www/NETPO101.mbr/menu');

		if (preg_match('%<input type="hidden" name="ws_contr" value="(.*)"%U', $res, $value)) $ws_contr = trim(rtrim(($value[1])));
		if (preg_match('%<input type="hidden" name="lk_codig" value="(.*)"%U', $res, $value)) $lk_codig = ($value[1]);
		$lk_chasn = $codigo;
		
		if($tipo == "chassi")
		{
			#$post = "&lk_divnm=div1&nu_docto=&tp_docto=&tipo_resposta=&nm_programa=MODULAR&ws_contr=$ws_contr+&co_usuario=$lk_codig&co_senha=$lk_codig&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=901&lk_fprop= &lk_fcnfp= &lk_fdbto= &lk_frbft= &lk_fdpvt= &lk_fgrvm=905&lk_fintg= &lk_fsnst= &lk_fdeco= &lk_fprec= &lk_fleil= &lk_fmoto= &lk_fcrvl= &lk_renav=on&lk_grvma=on&lk_chass=chassi&lk_chasn=$codigo&lk_chasc=&lk_ufede=&lk_tdoct=cpf&lk_docto=&lk_crvls=";		
			$post = "&lk_divnm=div1&nu_docto=&nu_docto_cpf=&nu_docto_cnpj=&tp_docto=&tipo_resposta=2&nm_programa=MODULAR&ws_contr=$ws_contr &co_usuario=$lk_codig&co_senha=$lk_codig&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&tp_consu=&ws_vhss=N&ws_vhps=N&ws_vvrs=N&ws_vvss=N&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=901&lk_fprop=   &lk_fcnfp=   &lk_fdbto=   &lk_frbft=   &lk_fdpvt=   &lk_fgrvm=   &lk_fintg=   &lk_fsnst=   &lk_fdeco=   &lk_fprec=   &lk_fleil=   &lk_fmoto=   &lk_fcrvl=   &lk_fvcdc=&lk_fhtpr=&ufx=&tipo_credito=OU&campo_1=&campo_2=&campo_3=&nu_cep=&nu_cep2=&agencia=&conta_corrente=&digito_conta=&cheque=&digito_cheque=&data_cheque=&quantidade=&valor_cheque=&score=N&modelo_score=&ponto_corte=&pt_corte=&CepConfirmacao=&nu_ddd=&nu_tel=&teste_rq=S&lk_renav=on&lk_tdopc=chassi&tab_fipe=on&lk_chasn=$codigo&lk_ufedex=&lk_chasc=$codigo&lk_ufede=&lk_placax=&lk_ufede_v=&lk_tdoct=cpf&lk_docto=&lk_crvls=&ano_de=&ano_ate=&ano_de2=&ano_ate2=";

		}
		
		if($tipo == "placa")
		{
			$post = "ws_contr=$ws_contr+&co_usuario=$lk_codig&co_senha=$lk_codig&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&nm_programa=MODULAR&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=902&lk_fprop=+++&lk_fcnfp=+++&lk_fdbto=+++&lk_frbft=+++&lk_fdpvt=+++&lk_fgrvm=+++&lk_fintg=+++&lk_fsnst=+++&lk_fdeco=+++&lk_fprec=+++&lk_fleil=+++&lk_fmoto=+++&lk_fcrvl=+++&lk_renav=on&lk_tdopc=placa&lk_chasn=$codigo&lk_chasc=&lk_ufede=&lk_tdoct=cpf&lk_docto=&lk_crvls=";		
		}
		
		if($tipo == "renavam")
		{
			$post = "ws_contr=$ws_contr+&co_usuario=$lk_codig&co_senha=$lk_codig&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&nm_programa=MODULAR&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=913&lk_fprop=+++&lk_fcnfp=+++&lk_fdbto=+++&lk_frbft=+++&lk_fdpvt=+++&lk_fgrvm=+++&lk_fintg=+++&lk_fsnst=+++&lk_fdeco=+++&lk_fprec=+++&lk_fleil=+++&lk_fmoto=+++&lk_fcrvl=+++&lk_renav=on&lk_tdopc=renavam&lk_chasn=$codigo&lk_chasc=&lk_ufede=&lk_tdoct=cpf&lk_docto=&lk_crvls=";
		}
		
		if($tipo == "motor")
		{			
			$post = "ws_contr=$ws_contr+&co_usuario=$lk_codig&co_senha=$co_senha&ws_menu=CERTOCAR&ws_frfs=N&ws_fgrs=N&ws_fins=N&ws_fprs=N&ws_fcns=N&nm_programa=MODULAR&nm_home=SIAPH049.php&lk_consu=MODULAR&lk_rvtip=999&lk_rvpar=$codigo&lk_frena=+++&lk_fprop=+++&lk_fcnfp=+++&lk_fdbto=+++&lk_frbft=+++&lk_fdpvt=+++&lk_fgrvm=+++&lk_fintg=+++&lk_fsnst=+++&lk_fdeco=+++&lk_fprec=+++&lk_fleil=+++&lk_fmoto=740&lk_fcrvl=+++&lk_motor=on&lk_chmot=motor&lk_chasn=$codigo&lk_chasc=&lk_ufede=&lk_tdoct=cpf&lk_docto=&lk_crvls=";
		}
		
		$res = $this->curl('https://www.scpcnet.com.br/ACSPNET//Programas/SIAPH055.php',$cookie,$post,'https://www.scpcnet.com.br/ACSPNET//Programas/SIAPH063.php');

		$full = $util->corta($res,'<div id=\'impressao\'>','</div>');
		$del = $util->corta($res,'<table class=\'table1\'>','</table>');
		$full = str_replace($del, '', $full);

		$del1 = $util->corta($res,'<table class="rodape">','</table>');
		$full = str_replace($del1, '', $full);

		$del2 = $util->corta($res,"<td class='tdFirst' >&nbsp;</td>
		<td class='tdDestaque_claro_campo' >
			Solicitante:
",'</td>
	</tr>');
		$full = str_replace($del2, '', $full);
		$full = str_replace("td class='tdFirst' >&nbsp;</td>
		<td class='tdDestaque_claro_campo' >
			Solicitante:", '', $full);

		$full = str_replace("  	<
</td>","</td>",$full);
		return ($full);
		die;
	}
	
	public function Juridica()
	{
		$this->Logar();
		
		if(strlen($this->getAcess()) < 2)
		{
			return "<pre>Sistema indisponivel no momento.</pre>";
			die;
		}
		$util = new Sistema_Util();
	
		$codigo = $this->getCpf();
		$codigo = str_replace(array('.','/','-'),'',$codigo);
		$lk_acess = $this->getAcess();
		$lk_codig = $this->getCodig();
		$ws_contr = $this->getContr();
		$cookie   = $this->getCookie();

		$res      = $this->curl("https://www.bvsnet.com.br/cgi-bin/db2www/NETPO020.mbr/juridica?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_tcons=ICA",'',null,null,"https://www.bvsnet.com.br/cgi-bin/db2www/NETPO020.mbr/juridica?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_tcons=ICA");
		$frm      = $this->parseForm($res);

		$lk_tcons = urlencode($frm['lk_tcons']);
		$lk_Antig = urlencode($frm['lk_Antig']);
		$lk_codig = urlencode($frm['lk_codig']);
		$ws_contr = urlencode($frm['ws_contr']);
		$lk_acess = urlencode($frm['lk_acess']);
		$lk_mscor = urlencode($frm['lk_mscor']);


		$postf   = "lk_tcons=ICA&lk_Antig=S&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_acess=$lk_acess&ws_menu=juridica&lk_tcgc1=CNPJ&lk_ncgc1=$codigo&lk_mscor=01&B1=Aguarde";
		$ref     = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO020.mbr/juridica?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_tcons=ICA";
		$urFinal = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO020.mbr/Consulta?";
		$full    = $this->curl($urFinal,$cookie,$postf,false,$ref);

		$full = $util->corta($full,'<PRE>','</PRE>');	
		$del  = $util->corta($full,'SOLICITANTE:','DOCUMENTOS:');
		if(strlen($del) == 0)
		{
			$full = "<>Sistema indisponivel no momento.";
		}
		else
		{
			$del1 = $util->corta($full,'+ + + + + +','</pre>');
			$full = str_replace($del1, '', $full);
			$full = str_replace('+ + + + + +', '', $full);
			$full = str_replace($del, '', $full);
			$full = str_replace('SOLICITANTE:', '', $full);
			$full = str_replace('CONSULTA:     SCPC', '', $full);
			$full = str_replace('RELATORIO SINTETICO NACIONAL', '<center><strong>RELATORIO SINTETICO NACIONAL</strong></center>', $full);
			
			
			$full = str_replace('href="', 'title="', $full);
			$full = str_replace('javascript:VerConsulta', '', $full);
			$full = str_replace('"(\'', '"', $full);
			$full = str_replace("','", '|', $full);
			$full = str_replace("');\">", '">', $full);
			$full = str_replace(' N A D A   C O N S T A', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>NADA CONSTA</strong>', $full);
		}
	
		$full = "<pre>".$full."</pre>";
		return $full;
		die;
	}
	
	public function Fisica()
	{
		$this->Logar();
		if(strlen($this->getAcess()) < 2)
		{
			echo "<pre>Sistema indisponivel no momento.</pre>";
			die;
		}
		else
		{
			$util = new Sistema_Util();
	
			$lk_acess = $this->getAcess();
			$lk_codig = $this->getCodig();
			$ws_contr = $this->getContr();
			$cookie   = $this->getCookie();

			$dados = $this->curl('https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/infocada?lk_acess='.$lk_acess.'&lk_codig='.$lk_codig.'&ws_contr='.$ws_contr.'&lk_conor=COMPLETO&lk_click=N',$cookie,'','');
			$frm   = $this->parseForm($dados);

			$lk_codig = urlencode($frm['lk_codig']);
			$ws_contr = urlencode($frm['ws_contr']);
			$lk_vslay = urlencode($frm['lk_vslay']);
			$lk_vscon = urlencode($frm['lk_vscon']);
			$lk_acess = urlencode($frm['lk_acess']);
			$ws_menu  = urlencode($frm['ws_menu']);
			
			$postf   = "lk_codig=$lk_codig&ws_contr=$ws_contr&lk_vslay=$lk_vslay&lk_consu=COMPN&lk_vscon=$lk_vscon&lk_acess=$lk_acess&ws_menu=$ws_menu&lk_tdoc1=CPF&lk_tcons=N&lk_tresp=2&lk_ndoc1=".$this->getCpf()."&lk_nuddd=&lk_nutel=&lk_tcred=OU&B1=+Aguarde+";
			$ur      = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/infocada?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_conor=COMPLETO&lk_click=N";
			$urFinal = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/Consulta1?";

			$final = $this->curl($urFinal,$cookie,$postf,$ur);
			$full = $util->corta($final,'<PRE>','</PRE>');
			$del  = $util->corta($final,'SOLICITANTE:','DOCUMENTOS:');

			if(strlen($del) == 0)
			{
				$lk_acess = $this->getAcess();
				$lk_codig = $this->getCodig();
				$ws_contr = $this->getContr();
				$cookie   = $this->getCookie();

				$dados = $this->curl('https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/infocada?lk_acess='.$lk_acess.'&lk_codig='.$lk_codig.'&ws_contr='.$ws_contr.'&lk_conor=COMPLETO&lk_click=N',$cookie,'','');
				$frm   = $this->parseForm($dados);

				$lk_codig = urlencode($frm['lk_codig']);
				$ws_contr = urlencode($frm['ws_contr']);
				$lk_vslay = urlencode($frm['lk_vslay']);
				$lk_vscon = urlencode($frm['lk_vscon']);
				$lk_acess = urlencode($frm['lk_acess']);
				$ws_menu  = urlencode($frm['ws_menu']);
				
				$postf   = "lk_codig=$lk_codig&ws_contr=$ws_contr&lk_vslay=$lk_vslay&lk_consu=COMPN&lk_vscon=$lk_vscon&lk_acess=$lk_acess&ws_menu=$ws_menu&lk_tdoc1=CPF&lk_tcons=N&lk_tresp=2&lk_ndoc1=".$this->getCpf()."&lk_nuddd=&lk_nutel=&lk_tcred=OU&B1=+Aguarde+";
				$ur      = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/infocada?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr&lk_conor=COMPLETO&lk_click=N";
				$urFinal = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO044.mbr/Consulta1?";

				$final = $this->curl($urFinal,$cookie,$postf,$ur);
				$full = $util->corta($final,'<PRE>','</PRE>');
				$del  = $util->corta($final,'SOLICITANTE:','DOCUMENTOS:');
				if(strlen($del) == 0)
				{
					$full =  "Sistema indisponivel no momento.";
				}
				else
				{
					$del1  = $util->corta($full,'+ + + + + +','</pre>');
					$full = str_replace($del1, '', $full);
					$full = str_replace('+ + + + + +', '', $full);
					$full = str_replace($del, '', $full);
				}
			}
			else
			{
				$del1  = $util->corta($full,'+ + + + + +','</pre>');
				$full = str_replace($del1, '', $full);
				$full = str_replace('+ + + + + +', '', $full);
				$full = str_replace($del, '', $full);
	
			}
			$full = "<pre>".$full."</pre>";
			return $full;
		}
	}

	public function Cheque($tipo,$lktipo,$lk1,$lk2,$lk3)
	{
		#die('Indisponivel');
		# tipo == CPF OU CNPJ
		#lktipo = CHEQUE+ ou CE
		$this->Logar();
		if(strlen($this->getAcess()) < 2)
		{
			echo "<pre>Sistema indisponivel no momento.</pre>";
			die;
		}
		else
		{
			$util = new Sistema_Util();
	
			$lk_acess = $this->getAcess();
			$lk_codig = $this->getCodig();
			$ws_contr = $this->getContr();
			$cookie   = $this->getCookie();

			$ur      = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO048.mbr/Cheque?lk_acess=$lk_acess&lk_codig=$lk_codig&ws_contr=$ws_contr";
			$urFinal = "https://www.bvsnet.com.br/cgi-bin/db2www/NETPO048.mbr/Consulta?";
			$postf   = "lk_codig=$lk_codig&ws_contr=$ws_contr&lk_acess=$lk_acess&ws_menu=Cons_SCPC&lk_consu=$lktipo&lk_tdoc1=CPF&lk_ndoc1=".$this->getCpf()."&lk_tdoc2=RG&lk_ndoc2=&lk_nuddd=&lk_nutel=&lk_tcred=XX&lk_nucep=&lk_cpcep=&B1=+Aguarde+";
			
			$postf   = "lk_codig=$lk_codig&ws_contr=$ws_contr&lk_acess=$lk_acess&lk_vslay=01&lk_vscon=10&ws_menu=Cheque&lk_consu=CE&lk_tdoc1=$tipo&lk_ndoc1=".$this->getCpf()."&lk_banco=$lk1&lk_cmc02=$lk2&lk_cmc03=$lk3&lk_agenc=&lk_conta=&lk_cdigi=&lk_chequ=&lk_digit=&lk_qtchq=&lk_dtchq=&lk_dtchc=&lk_valor=&lk_nuddd=&lk_nutel=&lk_nucep=&lk_cpcep=&B1=+Aguarde+
";

			$final = $this->curl($urFinal,$cookie,$postf,$ur);

			$full = $util->corta($final,'<PRE>','</PRE>');
			$del  = $util->corta($final,'SOLICITANTE:','DOCUMENTOS:');
			$full = str_replace('SOLICITANTE:', '', $full);

			if(strlen($del) == 0)
			{
				$full =  "Sistema indisponivel no momento.";
			}
			else
			{
				$del1  = $util->corta($full,'+ + + + + +','</pre>');
				$full = str_replace($del1, '', $full);
				$full = str_replace('+ + + + + +', '', $full);
				$full = str_replace($del, '', $full);
	
			}
			$full = "<pre>".$full."</pre>";
			return $full;
		}
	}

	
	function limparFisica($full)
	{
		$util = new Sistema_Util();
		
		$full = str_replace('SOLICITANTE:', '', $full);
		$full = str_replace('CONSULTA:     SCPC', '', $full);
		$full = str_replace('S C P C', 'Informações', $full);
			
		$rec  = $util->corta($full,'Informações <','------------>');
		$rec = trim(rtrim(str_replace('-', '', $rec)));

		$rec2  = $util->corta($full,'CONSULTAS ANTERIORES <','------------>');
		$rec2 = trim(rtrim(str_replace('-', '', $rec2)));

		if($rec == "N A D A   C O N S T A")
		{
			$full = str_replace(' N A D A   C O N S T A', '&nbsp;&nbsp;<strong>NADA CONSTA</strong>', $full);
			$full = str_replace('ANTERIORES <', 'ANTERIORES <----', $full);
			$full = str_replace('> Info', '-----> Info', $full);
			$full = str_replace('> CON', '-----> CON', $full);
		}
		elseif($rec2 == "N A D A   C O N S T A")
		{
			$full = str_replace(' N A D A   C O N S T A', '&nbsp;&nbsp;<strong>NADA CONSTA</strong>', $full);
			$full = str_replace('> CON', '---------> CON', $full);
		}
		
		$full = str_replace('DOCUMENTOS:', '<strong>DOCUMENTOS:</strong>', $full);
		$full = str_replace('Nome:', '<strong>Nome:</strong>', $full);
		$full = str_replace('Documentos:', '<strong>Documentos:</strong>', $full);
		$full = str_replace('==>', '<strong>==></strong>', $full);

		$full = str_replace('Informante', '<strong>Informante</strong>', $full);
		$full = str_replace('Contrato', '<strong>Contrato</strong>', $full);
		$full = str_replace('Debito', '<strong>Debito</strong>', $full);
		$full = str_replace('Disponivel', '<strong>Disponivel</strong>', $full);
		$full = str_replace('Informações', '<strong>Informações</strong>', $full);
		$full = str_replace('CONSULTAS ANTERIORES', '<strong>CONSULTAS ANTERIORES</strong>', $full);
		$full = str_replace('SINTESE CADASTRAL', '<strong>SINTESE CADASTRAL</strong>', $full);
		$full = str_replace('NOME:', '<strong>NOME:</strong>', $full);
		$full = str_replace('DOCUMENTO:', '<strong>DOCUMENTO:</strong>', $full);
		$full = str_replace('T.ELEITOR:', '<strong>T.ELEITOR:</strong>', $full);
		$full = str_replace('NASCIMENTO:', '<strong>NASCIMENTO:</strong>', $full);
		$full = str_replace('NOME MAE:', '<strong>NOME MAE:</strong>', $full);
		return $full;
	}
	
	public function parseForm($data)
	{
		$post = array();
		if(preg_match_all('/<input(.*)>/U', $data, $matches))
		{
			foreach($matches[0] as $input)
			{
				if(!stristr($input, "name=")) continue;
				if(preg_match('/name=(".*"|\'.*\')/U', $input, $name))
				{
					$key = substr($name[1], 1, -1);
					if(preg_match('/value=(".*"|\'.*\')/U', $input, $value)) $post[$key] = substr($value[1], 1, -1);
					else $post[$key] = "";
				}
			}
		}
		return $post;
	}

    public function Logar()
    {
		$util = new Sistema_Util();

		$post = $this->get_cc();
		$res = $this->curl("https://www.bvsnet.com.br/cgi-bin/db2www/NETPO001.mbr/senha?",null,$post,true,'https://www.bvsnet.com.br/cgi-bin/db2www/NETPO101.mbr/loginSI');

			if(stristr($res,'lk_opera'))
			{
				$frm = $this->parseForm($res);
				
				$post = 'lk_opera='.urlencode($frm['lk_opera']).'&lk_codig='.urlencode($frm['lk_codig']).'&lk_senha='.urlencode($frm['lk_senha']).'&ws_contr='.urlencode($frm['ws_contr']).'&lk_acess='.urlencode($frm['lk_acess']).'&lk_qdias='.urlencode($frm['lk_qdias']).'&lu_razao='.urlencode($frm['lu_razao']).'&lu_tpcli='.urlencode($frm['lu_tpcli']).'&lu_cdbvt='.urlencode($frm['lu_cdbvt']).'&lu_shbvt='.urlencode($frm['lu_shbvt']).'&lu_lunif='.urlencode($frm['lu_lunif']).'&lu_ludtm='.urlencode($frm['lu_ludtm']).'&lu_clinv='.urlencode($frm['lu_clinv']).'&lu_fgpop='.urlencode($frm['lu_fgpop']).'&lu_txpop='.urlencode($frm['lu_txpop']).'&lu_lnkpm='.urlencode($frm['lu_lnkpm']).'&ws_said5='.urlencode($frm['ws_said5']).'&lk_perfl='.urlencode($frm['lk_perfl']).'&lk_cnpj1='.urlencode($frm['lk_cnpj1']).'&lk_finan='.urlencode($frm['lk_finan']).'&lk_extju='.urlencode($frm['lk_extju']).'&lu_lgbvt='.urlencode($frm['lu_lgbvt']).'&lu_tkbvt='.urlencode($frm['lu_tkbvt']).'&lu_rlbvt='.urlencode($frm['lu_rlbvt']).'&lu_acspd='.urlencode($frm['lu_acspd']).'&lu_ipnum='.urlencode($frm['lu_ipnum']).'&lu_codpr='.urlencode($frm['lu_codpr']);
				$res = $this->curl("https://www.bvsnet.com.br/cgi-bin/db2www/NETPO101.mbr/menuLU",null,$post,true,'https://www.bvsnet.com.br/cgi-bin/db2www/NETPO001.mbr/senha?');

				if(stristr($res,'>Sua sess'))
				{
					$this->setAcess($frm['lk_acess']);
					$this->setCodig($frm['lk_codig']);
					$this->setContr($frm['ws_contr']);
				}
				else
				{
					die;
					//die($res);
				}
			}
			else
			{
				die('Tente novamente em instantes...');
			}
    }
	
	public function getLog()
	{
        $db  = $this->getDb();
		
		$stm = $db->prepare("select * from `logs_spc` where doc=:doc");
        $stm->bindValue(':doc', $this->getCpf());
		
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);    
        return $result;
	}
}