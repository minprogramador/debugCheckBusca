<?php

header('Content-Type: application/json'); 

/*

81423969049
92754738000162

*/

if(count($_POST) < 1) {
	$_POST = json_decode(file_get_contents('php://input'), true);
}

if(!isset($_GET['doc'])) {
	if(!isset($_POST['filtros'])) {
		$dados = array(
			'msg' => 'informe algum valor.',
			'code' => '404',
			'status' => false
		);
		echo json_encode($dados, JSON_PRETTY_PRINT);
		die;
	}
}

if(isset($_POST['filtros'])) {
	if(isset($_POST['filtros']['campos']['cpf'])){
		$doc = $_POST['filtros']['campos']['cpf']['documento'];
	}else if(isset($_POST['filtros']['campos']['cnpj'])){
		$doc = $_POST['filtros']['campos']['cnpj']['documento'];
	}
}else{
	$doc = $_GET['doc'];
}


$doc = str_replace(['/', '-', ' ', "\n", "\r", "\t"], '', $doc);

$mock = "data/{$doc}.json";

if(file_exists($mock)) {
	$dados   = file_get_contents($mock);
}else{
	$dados = json_encode(array(
		'code' => '404',
		'consultas' => array(
			"10" => array(
				"template" => "credito_3",
				"key" => array(
					"md5" => "85f5762d1923d38f86eb30cb145a883d",
					"title" => "92754738000162",
				),
			),
		),
		'status' => false
	), JSON_PRETTY_PRINT);
}

echo $dados;
die;

