<?php

/*
 * Pagina Loader
 * Responsavel por instanciar as classes
 * Versao: 1.0
 * criada em 14/10/2011
 * Desenvolvedor: Brunno duarte.
 * contato: brunos.duarte@hotmail.com
 */

function __autoload($class)
{
	$class = str_replace("_","/",$class).".php";
    require_once($class);	
}