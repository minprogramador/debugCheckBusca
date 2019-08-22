<?php

/*
* Pagina Config
* Responsavel por gerir o sistema
* Versao: 1.2
* criada em 20/02/2012 | update 10/03 | fix 24/09/2012
* Desenvolvedor: Brunno duarte | puttyoe.
* contato: brunos.duarte@hotmail.com
* contato: puttyoe@hotmail.com
*/

error_reporting(E_ALL);
set_include_path("lib/" . PATH_SEPARATOR . "lib/Sistema/" . PATH_SEPARATOR . "config/" . PATH_SEPARATOR . get_include_path());
session_start();

date_default_timezone_set('America/Sao_Paulo'); 

// Fix Session
ini_set('session.cache_expire', 180);
ini_set('session.cookie_httponly', true);
ini_set('session.use_only_cookie', 1);

if(!isset($_GET['sistema']))
{
    if(strpos(strtolower($_SERVER['REQUEST_URI']), 'phpsessid') !== false)
    {
        session_destroy();
        session_start();
        session_regenerate_id();
    }
}

# formato data
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('Brazil/East');

require_once("loader.php");
require_once("smarty.php");

$util = new Sistema_Util();

require_once("config.php");

$smarty->assign('PATCH',    "https://127.0.0.1");
$smarty->assign('LOGON',    1);
$smarty->assign('NOMESITE', NOMESITE);
$smarty->assign('EmailInfo',EmailInfo);

// if(isset($_SESSION['AlertMng']))
// {
//     $smarty->assign('error',$util->Msg('400px','error',$_SESSION['AlertMng'],''));
//     unset($_SESSION['AlertMng']);
// }

// if(isset($_SESSION['Sistema_Auth']['auth']))
// {
//     if(!isset($_SESSION['Captcha']))
//     {
//     	$smarty->assign('USUARIO',$_SESSION['getUsuario']);
// 		$smarty->assign('Logado',true);
//     }
// }

if(isset($_GET['limite']))
{
    $plano = new Sistema_Planos();
    $plano->setId($_SESSION['getId']);
    $smarty->assign('inPlano',$plano->ListaInfo());
    
    $smarty->assign("Pagina","Termino, limite de consultas.");
    $smarty->assign('Container','include_limite.html');
    $smarty->assign('Topo','include_topo.html');
    $smarty->assign('Rodape','include_rodape.html');
    $smarty->display('main.html');
    die;
}
elseif(isset($_GET['limiteRg']))
{
	echo "Seus pontos acabaram, entre em contato para comparar mais.";
	die;
}
elseif(isset($_GET['venceu']))
{
    $plano = new Sistema_Planos();
    $plano->setId($_SESSION['getId']);
    $smarty->assign('inPlano',$plano->ListaInfo());
    
    $smarty->assign("Pagina","Cadastro expirou.");
    $smarty->assign('Container','include_venceu.html');
    $smarty->assign('Topo','include_topo.html');
    $smarty->assign('Rodape','include_rodape.html');
    $smarty->display('main.html');
    die;
}
elseif(isset($_GET['negado']))
{
    $plano = new Sistema_Planos();
    $plano->setId($_SESSION['getId']);
    $smarty->assign('inPlano',$plano->ListaInfo());
	$smarty->assign("Pagina","Acesso Negado.");
    $smarty->assign('Container','include_bloqueio.html');
    $smarty->assign('Topo','include_topo.html');
    $smarty->assign('Rodape','include_rodape.html');
    $smarty->display('main.html');
    die;
}

if(isset($_GET['online']))
{
 //    if(isset($_SESSION['getDados']))
 //    {
 //        $on = new Sistema_Online();
 //        $on->setUsuario($_SESSION['getUsuario']);
 //        $conf = $on->Verifica();
 //        if($conf != "1")
 //        {
 //            $on->logout();
 //            foreach( $_SESSION as $Index => $Data ) { unset($_SESSION[$Index]); }
 //            foreach( $_COOKIE as $Index => $Data  ) { setcookie($Index, '', time()-172800); }
 //        }
 //        die;
 //    }
	// else
	// {
	// 	foreach( $_SESSION as $Index => $Data ) { unset($_SESSION[$Index]); }
	// 	foreach( $_COOKIE as $Index => $Data  ) { setcookie($Index, '', time()-172800); }
	// }
}

if(isset($_SESSION['getDados']))
{
/*     $on = new Sistema_Online();
    $on->setUsuario($_SESSION['getUsuario']);
	$user = new Sistema_Usuarios();
    $user->setUsuario($_SESSION['getUsuario']);
    $res = $user->getRes();

	if($on->Ver() <= $res['acessos'])
    {
        foreach($_SESSION as $Index => $Data)
        {
            unset($_SESSION[$Index]);
        }
        foreach($_COOKIE as $Index => $Data)
        {
            setcookie($Index, '', time()-172800);
        }
    } */
}
else
{
//    $on = new Sistema_Online();
//    $on->CountData();
}

//$getNav = $util->getBrowser();
//$smarty->assign("getBrowser",$getNav);
//$recado = new Sistema_Mensagem();
//$recado->setId(1);
//$smarty->assign("Recado",$recado->find());
#mensagem off-service

$mensagemControle = "Sistema fora do ar, tente mais tarde...";
