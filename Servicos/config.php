<?php
/*
 * Pagina Config
 * Responsavel por gerir o sistema
 * Versao: 1.0
 * criada em 28/02/2012 | update 10/03
 * Desenvolvedor: Brunno duarte.
 * contato: brunos.duarte@hotmail.com
 */

error_reporting(0); 
set_include_path("../lib/" . PATH_SEPARATOR . "../lib/Sistema/" . PATH_SEPARATOR . "../config/" . PATH_SEPARATOR . get_include_path());

#if(!isset($_SESSION['Sistema_Auth']['auth']))
#{
#    header("Location: https://checkbusca.com/Sair");
#    die;
#}
// Fix Session
// ini_set('session.cache_expire', 180);
// ini_set('session.cookie_httponly', true);
// ini_set('session.use_only_cookie', 1);

// session_start();

// if($_SESSION['getStatus'] == 4)
// {
// 	header("Location: ../FormaPagamento");
// 	die;
// }
// elseif($_SESSION['getStatus'] == 8)
// {
// 	header("Location: ../FormaPagamento");
// 	die;
// }

// if(!isset($_GET['sistema']))
// {
// 	if(strpos(strtolower($_SERVER['REQUEST_URI']), 'phpsessid') !== false)
// 	{
// 		session_destroy();
// 		session_start();
// 		session_regenerate_id();
// 	}
// }
# formato data
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('Brazil/East');
setlocale(LC_ALL,'ptb');

require_once("loader.php");

$util = new Sistema_Util();
$url  = $util->UrlPatch();
define("PATCH",   'https://checkbusca.com/Servicos');
require_once("config.php");


$conf = new Sistema_Configuracao();
$site = $conf->getDados();

/*Define*/
define("NOMESITE",'https://checkbusca.com');
define("LOGON",   1);
define("CAPTCHA", 1);

require_once("Smarty/libs/Smarty.class.php");

$smarty = new Smarty();
$smarty->template_dir = "../tpls/admin";
$smarty->compile_dir  = "../.cache";
spl_autoload_register('__autoload');

$smarty->assign('PATCH','https://checkbusca.com');
$smarty->assign('LOGON',1);
$smarty->assign('NOMESITE',1);

if(isset($_SESSION['AlertMng']))
{
    $smarty->assign('IconAlert',$_SESSION['IconAler']);
    $smarty->assign('error',$_SESSION['AlertMng']);
    unset($_SESSION['AlertMng']);
    unset($_SESSION['IconAler']);
}

$util = new Sistema_Util();

if(isset($_GET['id']))
{
    $id = $util->xss($_GET['id']);
}

$mensagemControle = "Este sistema esta em manutencao volta em  24 horas! tente outras opcoes.";


//$urltoken = 'http://upverify.net/api/consulta/consultar';
$urltoken = 'http://localhost/apis/mock.php';
$token    = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NTQ5MzQ0NjksImV4cCI6NDEwMjQ1MTk5OSwiZGF0YSI6IjE3OCJ9.PzsdkbzEVKtOgull2om14ATL6DJNsqfJR5LLUsT8gLc';
