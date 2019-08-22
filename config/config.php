<?php
/*
* Pagina Config - Mysql e informacoes do site.
* Responsavel por gerir os dados de login do Mysql
* Versao: 1.1
* criada em 14/10/2011 / atualizado em 24/09/2012
* Desenvolvedor: Brunno duarte.
* contato: brunos.duarte@hotmail.com
*/


define("PATCH",     "http://127.0.0.1"); # Url do site.

$config['adapter']  = 'mysql';
$config['hostname'] = '';
$config['dbname']   = ''; # Nome Banco de dados - Mysql
$config['user']     = ''; # Usuario Mysql
$config['password'] = '';      # Senha Mysql

# Define Informacoes do site #

define("NOMESITE",  ""); # Nome do site
define("LOGON",     1);             # Pedir login   1 == sim  ? 2 == nao
define("CAPTCHA",   1);             # Pedir captcha 1 == sim  ? 2 == nao
define("EmailInfo", ""); # E-mail para onde vai o contato e info de cadastro

# Define Informacoes do site #