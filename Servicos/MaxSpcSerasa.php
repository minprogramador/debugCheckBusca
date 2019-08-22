<?php


//$urltoken = 'http://127.0.0.1:8080/apis/mock.php';
$urltoken = 'http://127.0.0.1:8080/apis/mock.php';
$token    = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NTQ5MzQ0NjksImV4cCI6NDEwMjQ1MTk5OSwiZGF0YSI6IjE3OCJ9.PzsdkbzEVKtOgull2om14ATL6DJNsqfJR5LLUsT8gLc';


require '../lib/Sistema/Db/Abstract.php';
require '../lib/Sistema/Db/Connection.php';
require '../lib/Sistema/Usuarios.php';
require '../lib/Sistema/ControlGeral.php';
require '../lib/Sistema/Servicos.php';

$NomeServico = 'max-spc-serasa';

$servico = new Sistema_Servicos();
$servico->setServico($NomeServico);

// $control = new Sistema_ControlGeral();
// $control->setServico($NomeServico);
// $control->setIdUser($_SESSION['getId']);

// if($control->Permissao() != true){
//     $msg = 'Você nao tem permissão para acessar esse serviço!';
// }else {
//     $a = $control->getLimites();
//     if($a['status'] != '1') {
//         $msg = 'Você não tem permissão para usar esse serviço.';
//     }
//     if($a['usado'] >= $a['limite']) {
//         $msg = 'Seu limite acabou, para adiquirir mais entre em contato!';
//     }
// }

// if($dads['status'] == '0') {
//     $msg = 'Indisponivel no momento!';
// }

$a = array(
    'limite' => 100,
    'usado' => 0,
    'status' => true
);


// if(!isset($msg)) {
//     if(date('H') >= 23) {
//         if(date('H') == 23) {
//             if(date('i') >= 59) {
//                 $msg = 'Horario de funcionamento, 08:00 até 23:59!';
//             }
//         }else{
//             $msg = 'Horario de funcionamento, 08:00 até 23:59!';
//         }
//     }elseif(date('H') < 8) {
//             $msg = 'Horario de funcionamento, 08:00 até 23:59!';
//     }
// }

if(isset($msg)){
    $tpl = file_get_contents('./tpls/Max-spc-serasa/msg.html');
    $tpl = str_replace('{{msg}}', $msg, $tpl);
    $tpl = str_replace(array("\n", "\r", "\t", "  ", "  "), '', $tpl);
    echo $tpl;
    die;
}

require('funcs.php');

function saveLog($doc, $res) {
    return true;
    // $dia = date('d-m-Y');
    // $pasta = "Bin/cache_Max-spc-serasa/{$dia}";
    // if(!is_dir($pasta)) {
    //     @mkdir ($pasta);
    // }

    // $doc = $pasta. '/' . $doc . '_' .date("d-m-Y").' '.date("H:i:s") . '.json';

    // if(file_put_contents($doc, $res)) {
    //     return true;
    // }
    // return false;
}

function consultar($doc, $urltoken, $token) {

    if(preg_match("#^[0-9]{2}?[0-9]{3}?[0-9]{3}?[0-9]{4}?[0-9]{2}$#i", $doc)) {
        $tipo = "cnpj";
    }elseif(preg_match("#^([0-9]){3}([0-9]){3}([0-9]){3}([0-9]){2}$#i", $doc)) {
        $tipo = "cpf";
    }else{
       return 'doc_invalido';
    }


    $post = json_encode([
        "aba" => $tipo,
        "filtros" => [
            "campos" => [
                $tipo => [
                    "documento" => $doc
                ]
            ],
            "empresas" => [
                10 => true
            ],
            "opcionais" => []
        ],
        "sistema" => 2
    ]);

    $header = [
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($post),
        'Authorization: Bearer '.$token,
        'fingerprint: API'
    ];
    // echo $urltoken;
    // die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urltoken); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $output   = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    saveLog($doc, $output);

    if(stristr($output, 'correu alguma falha na consul')) {
        return 'erro_desconhecido';
    }

    if(!stristr($output, 'identificacao')) {
        return 'indisponivel';
    }

    $json = json_decode($output, TRUE);
    $erro = NULL;
    $resultado = NULL;

    if($httpcode == 200){
        $sistema = 10;
        if(isset($json['data'])) {
            if(isset($json['data']['erros'][$sistema])) {
                $erro = $json['data']['erros'][$sistema];
            }else if(isset($json['data']['consultas'][$sistema])){
                $resultado = $json['data']['consultas'][$sistema]['data'];
            }else{
                $erro = "Erro desconhecido [1]";
            }
        }else{
            $erro = "Erro desconhecido [2]";
        }
    }else{
        $erro = (isset($json['msg'])) ? $json['msg'] : "Erro desconhecido [3]";
    }

    if($erro) {
        return 'erro_desconhecido';
    }

    return $resultado;
}

function participacoesEmpresas($resultado) {
    if(!is_array($resultado)) {
        return false;
    }

    if(array_key_exists('identificacao', $resultado)) {
        $identificacao     = $resultado['identificacao'];
        $endereco          = $resultado['endereco'];
        $enderecoanterior  = $resultado['enderecosInformadosAnteriormente'];
    }else{
        $identificacao = false;
    }

    if(array_key_exists('resumoOcorrencias', $resultado)) {
        $ocorrencias = $resultado['resumoOcorrencias'];
    }else{
        $ocorrencias = false;
    }

    if(array_key_exists('score', $resultado)) {
        $score = $resultado['score'];
    }else{
        $score = false;
    }


    if(array_key_exists('consultaSpcSerasa', $resultado)) {
        $consultaSpcSerasa = $resultado['consultaSpcSerasa'];
    }else{
        $consultaSpcSerasa = false;
    }

    if(array_key_exists('protestos', $resultado)) {
        $protestos = $resultado['protestos'];
    }else{
        $protestos = array();
    }

    if(array_key_exists('telefones', $resultado)) {
        $telefones = $resultado['telefones'];
    }else{
        $telefones = array();
    }

    if(array_key_exists('participacoesEmpresas', $resultado)) {
        $participacoesEmpresas = $resultado['participacoesEmpresas'];
    }else{
        $participacoesEmpresas = array();
    }

    if(array_key_exists('pendenciasSPC', $resultado)) {
        $pendenciasSPC = $resultado['pendenciasSPC'];
    }else{
        $pendenciasSPC = array();
    }

    if(array_key_exists('pendenciasFinanceiraSerasa', $resultado)) {
        $pendenciasFinanceiraSerasa = $resultado['pendenciasFinanceiraSerasa'];
    }else{
        $pendenciasFinanceiraSerasa = array();
    }

    if(array_key_exists('chequeSemFundo', $resultado)) {
        $chequeSemFundo = $resultado['chequeSemFundo'];
    }else{
        $chequeSemFundo = array();
    }

    $cpf = $identificacao['cpf'];
    if(array_key_exists('situacao_do_cpf', $identificacao)) {
        $situacao_do_cpf = $identificacao['situacao_do_cpf'];
    }else{
        $situacao_do_cpf = '';
    }

    if(array_key_exists('data_da_inscricao_do_cpf', $identificacao)) {
        $data_da_inscricao_do_cpf = $identificacao['data_da_inscricao_do_cpf'];
    }else{
        $data_da_inscricao_do_cpf = '';
    }

    $nome = $identificacao['nome'];
    if(array_key_exists('data_de_nascimento', $identificacao)) {
        $data_de_nascimento = $identificacao['data_de_nascimento']['date'];
        if(stristr($data_de_nascimento, '-')){
           $data_de_nascimento =  date('d/m/Y', strtotime($data_de_nascimento));
        }
    }else{
        $data_de_nascimento = '';
    }

    $nome_da_mae = $identificacao['nome_da_mae'];

    $endereco = $resultado['endereco']['endereco'];
    $bairro   = $resultado['endereco']['bairro'];
    $cidade   = $resultado['endereco']['cidade'];
    $uf       = $resultado['endereco']['uf'];
    $cep      = $resultado['endereco']['cep'];
    if(is_array($score) && array_key_exists('pontuacao', $score)) {
        $scorepontos = $score['pontuacao'];
    }else{
        $scorepontos = '-';
    }

    $ocorrenc       = jsonToTd2($ocorrencias);
    $endAnt         = jsonToTd($enderecoanterior);
    $telefone_anter = jsonToTd($telefones);
    $participaEmpre = jsonToTd($participacoesEmpresas);
    $protestos      = jsonToTd($protestos);
    $pendenciasSPC  = jsonToTd($pendenciasSPC);
    $chequeSemFundo = jsonToTd($chequeSemFundo);
    $consultaSpcSerasa = jsonToTd($consultaSpcSerasa);
    $pendenciasFinanceiraSerasa = jsonToTd($pendenciasFinanceiraSerasa);

    if($data_da_inscricao_do_cpf != '') {
        $data_da_inscricao_do_cpf = '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Data da Inscrição do CPF:&nbsp;<span id="filtro_cpf_span">'.$data_da_inscricao_do_cpf.'</span>';
    }
    if($situacao_do_cpf != '') {
        $situacao_do_cpf = '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Situação do CPF: <span id="filtro_cpf_span">'.$situacao_do_cpf.'</span>';
    }

    $dados = file_get_contents('tpls/Max-spc-serasa/resultadook.html');

    $dados = str_replace('{{cpf}}', $cpf, $dados);
    $dados = str_replace('{{situacao_do_cpf}}', $situacao_do_cpf, $dados);
    $dados = str_replace('{{data_da_inscricao_do_cpf}}', $data_da_inscricao_do_cpf, $dados);
    $dados = str_replace('{{nome}}', $nome, $dados);
    $dados = str_replace('{{data_de_nascimento}}', $data_de_nascimento, $dados);
    $dados = str_replace('{{nome_da_mae}}', $nome_da_mae, $dados);
    $dados = str_replace('{{endereco}}', $endereco, $dados);
    $dados = str_replace('{{bairro}}', $bairro, $dados);
    $dados = str_replace('{{cidade}}', $cidade, $dados);
    $dados = str_replace('{{uf}}', $uf, $dados);
    $dados = str_replace('{{cep}}', $cep, $dados);
    $dados = str_replace('{{endereco_anterior}}', $endAnt, $dados);

    $dados = str_replace('{{telefone_anter}}', $telefone_anter, $dados);
    $dados = str_replace('{{telefone_anter}}', $participaEmpre, $dados);



    $dados = str_replace('{{score.pontuacao}}', $scorepontos, $dados);
    $dados = str_replace('{{score.descricao}}', $score['descricao'], $dados);
    $dados = str_replace('{{ocorrenc}}', $ocorrenc, $dados);
    $dados = str_replace('{{consultaSpcSerasa}}', $consultaSpcSerasa, $dados);
    $dados = str_replace('{{pendenciasFinanceiraSerasa}}', $pendenciasFinanceiraSerasa, $dados);
    $dados = str_replace('{{protestos}}', $protestos, $dados);
    $dados = str_replace('{{pendenciasSPC}}', $pendenciasSPC, $dados);
    $dados = str_replace('{{chequeSemFundo}}', $chequeSemFundo, $dados);

    return json_encode(array('dados' => $dados));
}


function filtrarCnpj($resultado) {
    if(!is_array($resultado)) {
        return false;
    }

    if(array_key_exists('identificacao', $resultado)) {
        $identificacao     = $resultado['identificacao'];
        $endereco          = $resultado['endereco'];
        $enderecoanterior  = $resultado['enderecosInformadosAnteriormente'];
    }else{
        $identificacao = false;
    }

    if(array_key_exists('resumoOcorrencias', $resultado)) {
        $ocorrencias = $resultado['resumoOcorrencias'];
    }else{
        $ocorrencias = false;
    }

    if(array_key_exists('score', $resultado)) {
        $score = $resultado['score'];
    }else{
        $score = false;
    }

    if(array_key_exists('telefones', $resultado)) {
        $telefones = $resultado['telefones'];
    }else{
        $telefones = false;
    }


    if(array_key_exists('participacoesEmpresas', $resultado)) {
        $participacoesEmpresas = $resultado['participacoesEmpresas'];
    }else{
        $participacoesEmpresas = false;
    }



    if(array_key_exists('consultaSpcSerasa', $resultado)) {
        $consultaSpcSerasa = $resultado['consultaSpcSerasa'];
    }else{
        $consultaSpcSerasa = false;
    }

    if(array_key_exists('protestos', $resultado)) {
        $protestos = $resultado['protestos'];
    }else{
        $protestos = array();
    }

    if(array_key_exists('pendenciasSPC', $resultado)) {
        $pendenciasSPC = $resultado['pendenciasSPC'];
    }else{
        $pendenciasSPC = array();
    }

    if(array_key_exists('pendenciasFinanceiraSerasa', $resultado)) {
        $pendenciasFinanceiraSerasa = $resultado['pendenciasFinanceiraSerasa'];
    }else{
        $pendenciasFinanceiraSerasa = array();
    }

    if(array_key_exists('chequeSemFundo', $resultado)) {
        $chequeSemFundo = $resultado['chequeSemFundo'];
    }else{
        $chequeSemFundo = array();
    }

    $cnpj = $identificacao['cnpj'];
    if(array_key_exists('situacao_do_cnpj', $identificacao)) {
        $situacao_do_cnpj = $identificacao['situacao_do_cnpj'];
    }else{
        $situacao_do_cnpj = '';
    }

    $atividade_economica_principal = $identificacao['atividade_economica_principal'];
    if(array_key_exists('data_da_fundacao', $identificacao)) {
        $data_da_fundacao = $identificacao['data_da_fundacao']['date'];
        if(stristr($data_da_fundacao, '-')){
           $data_da_fundacao =  date('d/m/Y', strtotime($data_da_fundacao));
        }
    }else{
        $data_da_fundacao = '';
    }

    $razao_social = $identificacao['razao_social'];

    $endereco = $resultado['endereco']['endereco'];
    $bairro   = $resultado['endereco']['bairro'];
    $cidade   = $resultado['endereco']['cidade'];
    $uf       = $resultado['endereco']['uf'];
    $cep      = $resultado['endereco']['cep'];
    if(is_array($score) && array_key_exists('pontuacao', $score)) {
        $scorepontos = $score['pontuacao'];
    }else{
        $scorepontos = '-';
    }

    $identific = '<table class="table table-border-outside">
        <thead class="thead-dark">
          <tr>
            <th class="panel" colspan="2">
              <div class="icontxt"></div>&nbsp;IDENTIFICAÇÃO
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td_dark_2colunas_menor">
              CNPJ: <span id="filtro_cpf_span">'.$cnpj.'</span>
            </td>
          </tr>
          <tr>
            <td class="td_dark_2colunas_menor">Razão social:<span id="filtro_cpf_span">'.$razao_social.'</span></td>
          </tr>

          <tr>
            <td class="td_dark_2colunas_menor">
            Situaçao: <span id="filtro_cpf_span"><strong>'.$situacao_do_cnpj.'</strong></span>
            </td>
          </tr>

          <tr>
            <td class="td_dark_2colunas_menor">
            Data Fundação: <span id="filtro_cpf_span"><strong>'.$data_da_fundacao.'</strong></span>
            </td>
          </tr>

          <tr>

            <td class="td_dark_2colunas_menor">
              Atividade economica principal: <span id="filtro_cpf_span">'.$atividade_economica_principal.'</span>
            </td>
          </tr>
        </tbody>
      </table>';

    $ocorrenc       = jsonToTd2($ocorrencias);
    $endAnt         = jsonToTd($enderecoanterior);
    $protestos      = jsonToTd($protestos);
    $pendenciasSPC  = jsonToTd($pendenciasSPC);
    $chequeSemFundo = jsonToTd($chequeSemFundo);
    $consultaSpcSerasa = jsonToTd($consultaSpcSerasa);
    $pendenciasFinanceiraSerasa = jsonToTd($pendenciasFinanceiraSerasa);

    $telefonesOk = jsonToTd($telefones);
    $participEmp = jsonToTd($participacoesEmpresas);

    $dados = file_get_contents('tpls/Max-spc-serasa/resultadookcnpj.html');


    $dados = str_replace('{{identific}}', $identific, $dados);
    $dados = str_replace('{{nome}}', $razao_social, $dados);
    $dados = str_replace('{{data_da_fundacao}}', $data_da_fundacao, $dados);
    $dados = str_replace('{{nome}}', $razao_social, $dados);
    $dados = str_replace('{{endereco}}', $endereco, $dados);
    $dados = str_replace('{{bairro}}', $bairro, $dados);
    $dados = str_replace('{{cidade}}', $cidade, $dados);
    $dados = str_replace('{{uf}}', $uf, $dados);
    $dados = str_replace('{{cep}}', $cep, $dados);
    $dados = str_replace('{{endereco_anterior}}', $endAnt, $dados);
    $dados = str_replace('{{score.pontuacao}}', $scorepontos, $dados);
    $dados = str_replace('{{score.descricao}}', $score['descricao'], $dados);
    $dados = str_replace('{{ocorrenc}}', $ocorrenc, $dados);
    $dados = str_replace('{{consultaSpcSerasa}}', $consultaSpcSerasa, $dados);
    $dados = str_replace('{{pendenciasFinanceiraSerasa}}', $pendenciasFinanceiraSerasa, $dados);
    $dados = str_replace('{{protestos}}', $protestos, $dados);
    $dados = str_replace('{{pendenciasSPC}}', $pendenciasSPC, $dados);
    $dados = str_replace('{{chequeSemFundo}}', $chequeSemFundo, $dados);


    $dados = str_replace('{{telefones_vinculados}}', $telefonesOk, $dados);
    $dados = str_replace('{{participacao_empresas}}', $participEmp, $dados);

    


    return json_encode(array('dados' => $dados));
}

function filtrar($resultado) {
    if(!is_array($resultado)) {
        return false;
    }

    if(array_key_exists('identificacao', $resultado)) {
        $identificacao     = $resultado['identificacao'];
        $endereco          = $resultado['endereco'];
        $enderecoanterior  = $resultado['enderecosInformadosAnteriormente'];
    }else{
        $identificacao = false;
    }

    if(array_key_exists('telefones', $resultado)) {
        $telefones = $resultado['telefones'];
    }else{
        $telefones = false;
    }

    if(array_key_exists('participacoesEmpresas', $resultado)) {
        $participEmpresas = $resultado['participacoesEmpresas'];
    }else{
        $participEmpresas = false;
    }

    if(array_key_exists('resumoOcorrencias', $resultado)) {
        $ocorrencias = $resultado['resumoOcorrencias'];
    }else{
        $ocorrencias = false;
    }

    if(array_key_exists('score', $resultado)) {
        $score = $resultado['score'];
    }else{
        $score = false;
    }


    if(array_key_exists('consultaSpcSerasa', $resultado)) {
        $consultaSpcSerasa = $resultado['consultaSpcSerasa'];
    }else{
        $consultaSpcSerasa = false;
    }

    if(array_key_exists('protestos', $resultado)) {
        $protestos = $resultado['protestos'];
    }else{
        $protestos = array();
    }

    if(array_key_exists('pendenciasSPC', $resultado)) {
        $pendenciasSPC = $resultado['pendenciasSPC'];
    }else{
        $pendenciasSPC = array();
    }

    if(array_key_exists('pendenciasFinanceiraSerasa', $resultado)) {
        $pendenciasFinanceiraSerasa = $resultado['pendenciasFinanceiraSerasa'];
    }else{
        $pendenciasFinanceiraSerasa = array();
    }

    if(array_key_exists('chequeSemFundo', $resultado)) {
        $chequeSemFundo = $resultado['chequeSemFundo'];
    }else{
        $chequeSemFundo = array();
    }

    $cpf = $identificacao['cpf'];
    if(array_key_exists('situacao_do_cpf', $identificacao)) {
        $situacao_do_cpf = $identificacao['situacao_do_cpf'];
    }else{
        $situacao_do_cpf = '';
    }

    if(array_key_exists('data_da_inscricao_do_cpf', $identificacao)) {
        $data_da_inscricao_do_cpf = $identificacao['data_da_inscricao_do_cpf'];
    }else{
        $data_da_inscricao_do_cpf = '';
    }

    $nome = $identificacao['nome'];
    if(array_key_exists('data_de_nascimento', $identificacao)) {
        $data_de_nascimento = $identificacao['data_de_nascimento']['date'];
        if(stristr($data_de_nascimento, '-')){
           $data_de_nascimento =  date('d/m/Y', strtotime($data_de_nascimento));
        }
    }else{
        $data_de_nascimento = '';
    }

    $nome_da_mae = $identificacao['nome_da_mae'];

    $endereco = $resultado['endereco']['endereco'];
    $bairro   = $resultado['endereco']['bairro'];
    $cidade   = $resultado['endereco']['cidade'];
    $uf       = $resultado['endereco']['uf'];
    $cep      = $resultado['endereco']['cep'];
    if(is_array($score) && array_key_exists('pontuacao', $score)) {
        $scorepontos = $score['pontuacao'];
    }else{
        $scorepontos = '-';
    }

    $ocorrenc       = jsonToTd2($ocorrencias);
    $endAnt         = jsonToTd($enderecoanterior);
    $protestos      = jsonToTd($protestos);
    $pendenciasSPC  = jsonToTd($pendenciasSPC);
    $chequeSemFundo = jsonToTd($chequeSemFundo);
    $consultaSpcSerasa = jsonToTd($consultaSpcSerasa);
    $pendenciasFinanceiraSerasa = jsonToTd($pendenciasFinanceiraSerasa);

    $telefones_vinculados = jsonToTd($telefones);
    $participacao_empresarial = jsonToTd($participEmpresas);


    if($data_da_inscricao_do_cpf != '') {
        $data_da_inscricao_do_cpf = '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Data da Inscrição do CPF:&nbsp;<span id="filtro_cpf_span">'.$data_da_inscricao_do_cpf.'</span>';
    }
    if($situacao_do_cpf != '') {
        $situacao_do_cpf = '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Situação do CPF: <span id="filtro_cpf_span">'.$situacao_do_cpf.'</span>';
    }

    $dados = file_get_contents('tpls/Max-spc-serasa/resultadook.html');

    $dados = str_replace('{{cpf}}', $cpf, $dados);
    $dados = str_replace('{{situacao_do_cpf}}', $situacao_do_cpf, $dados);
    $dados = str_replace('{{data_da_inscricao_do_cpf}}', $data_da_inscricao_do_cpf, $dados);
    $dados = str_replace('{{nome}}', $nome, $dados);
    $dados = str_replace('{{data_de_nascimento}}', $data_de_nascimento, $dados);
    $dados = str_replace('{{nome_da_mae}}', $nome_da_mae, $dados);
    $dados = str_replace('{{endereco}}', $endereco, $dados);
    $dados = str_replace('{{bairro}}', $bairro, $dados);
    $dados = str_replace('{{cidade}}', $cidade, $dados);
    $dados = str_replace('{{uf}}', $uf, $dados);
    $dados = str_replace('{{cep}}', $cep, $dados);
    $dados = str_replace('{{endereco_anterior}}', $endAnt, $dados);
    $dados = str_replace('{{score.pontuacao}}', $scorepontos, $dados);
    $dados = str_replace('{{score.descricao}}', $score['descricao'], $dados);
    $dados = str_replace('{{ocorrenc}}', $ocorrenc, $dados);
    $dados = str_replace('{{consultaSpcSerasa}}', $consultaSpcSerasa, $dados);
    $dados = str_replace('{{pendenciasFinanceiraSerasa}}', $pendenciasFinanceiraSerasa, $dados);
    $dados = str_replace('{{protestos}}', $protestos, $dados);
    $dados = str_replace('{{pendenciasSPC}}', $pendenciasSPC, $dados);
    $dados = str_replace('{{chequeSemFundo}}', $chequeSemFundo, $dados);

    $dados = str_replace('{{telefones_vinculados}}', $telefones_vinculados, $dados);
    $dados = str_replace('{{participacao_empresas}}', $participacao_empresarial, $dados);

    return json_encode(array('dados' => $dados));
}


if(isset($_POST['dados'])) {

    // echo "<pre>Post dados:\n";

    // print_r($_POST);
    // die;

    // $dados = array('msg' => 'nadaencontrado');
    // $dados = array('msg' => 'reload');
    // $dados = array('msg' => 'fail');
    // $dados = array('msg' => 'invalido');

    $documento = xss($_POST['dados']);
    $documento = str_replace(array('.', ',', '-', '/', ' ', '_', "\t", "\n", "\r"), '', $documento);
    $resultado = consultar($documento, $urltoken, $token);

    if($resultado == 'erro_desconhecido') {
        $dados = array('msg' => 'fail');
        $dados = json_encode($dados);
    }elseif($resultado == 'indisponivel') {
        $dados = array('msg' => 'nadaencontrado');
        $dados = json_encode($dados);
    }else{

        if(strlen($documento) == 11) {
            $dados = filtrar($resultado);
        }else{
            $dados = filtrarCnpj($resultado);
        }

        if($dados !== false) {
            if(stristr($dados, 'IDENTIFICA')) {
                // $control->saveConsulta();
                // $comp = new Sistema_Verificacao();
                // $comp->setServico($NomeServico);
                // $comp->Computa();
                print_r($dados);
                die;
            }
        }else{
            $dados = array('msg' => 'nadaencontrado');
            $dados = json_encode($dados);
        }        
    }
}else{
    $tpl = file_get_contents('tpls/Max-spc-serasa/index.html');
    $tpl = str_replace(array("\n", "\r", "\t", "  "), '', $tpl);
    $tpl = str_replace('<h6>LIMITE: <strong> 0</strong> - USADO: <strong> 0</strong></h6>', 
        '<h6>LIMITE: <strong> '.$a['limite'].'</strong> - USADO: <strong> '.$a['usado'].'</strong></h6>', $tpl);
    $dados = $tpl;
}

echo $dados;