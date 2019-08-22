<?php
use PHPUnit\Framework\TestCase;

class MaxSpcSerasaTest extends TestCase
{
	public function mockDados() {
		$mock = (object)[
			'cpf' => '111.111.111-11',
			'situacao_do_cpf' => '',
			'data_da_inscricao_do_cpf' => '',
			'nome' => 'manoel da silva',
			'data_de_nascimento' => '',
			'nome_da_mae' => '',
			'endereco' => '',
			'bairro' => '',
			'cidade' => '',
			'uf' => '',
			'cep' => '',
			'endereco_anterior' => '',
			'score.pontuacao' => '',
			'score.descricao' => '',
			'ocorrenc' => '',
			'consultaSpcSerasa' => '',
			'pendenciasFinanceiraSerasa' => '',
			'protestos' => '',
			'pendenciasSPC' => '',
			'chequeSemFundo' => ''
		];

		return $mock;
	}

	public function buildDados($dados) {
		$dadosMock = $dados;

		$tpl = __DIR__ . '/../Servicos/tpls/Max-spc-serasa/resultadook.html';
		$dados = file_get_contents($tpl);
	    $dados = str_replace('{{cpf}}', $dadosMock->cpf, $dados);
	    $dados = str_replace('{{nome}}', $dadosMock->nome, $dados);
		return $dados;		
	}

	public function testContemOCpfValido() {
		$dadosMock = $this->mockDados();
		$dados = $this->buildDados($dadosMock);
		$this->assertStringContainsString('111.111.111-11', $dados);

	}

	public function testContemNomeValido() {
		$dadosMock = $this->mockDados();
		$dados = $this->buildDados($dadosMock);
		$this->assertStringContainsString('manoel da silva', $dados);		
	}



	public function testMontaLayout() {
		$dadosMock = $this->mockDados();
		$dados = $this->buildDados($dadosMock);

		$this->assertStringContainsString('111.111.111-11', $dados);
	}

    public function testDebugRetornoJson() {
    	$arquivo = __DIR__.'/../apis/data/81423969049.json';
    	$dados = file_get_contents($arquivo);
    	$dados = json_decode($dados);

    	$this->assertEquals(true, $dados->code);
    }


    public function testLayoutTelefoneOk() {

		$dadosMock = $this->mockDados();
		$dados = $this->buildDados($dadosMock);
		$this->assertStringContainsString('{{telefones_vinculados}}', $dados);		

    	
    }


}