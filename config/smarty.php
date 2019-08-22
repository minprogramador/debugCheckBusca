<?php

/*
* Pagina Smarty
* Responsavel por instanciar o smarty php
* Versao: 1.0
* criada em 14/10/2011
* Desenvolvedor: Brunno duarte.
* contato: brunos.duarte@hotmail.com
*/

require_once("Smarty/libs/Smarty.class.php");

$smarty = new Smarty();
$smarty->template_dir = "tpls";
$smarty->compile_dir  = ".cache";
spl_autoload_register('__autoload');