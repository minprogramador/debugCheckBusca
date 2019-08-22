<?php

class Sistema_Moip
{
    private $auth = "";
    private $periodo = null;
    private $valor   = null;
    private $nome    = null;
    private $email   = null;
    
    private $logradouro = null;
    private $numero     = null;
    private $bairro     = null;
    private $cidade     = null;
    private $estado     = null;
    private $cep        = null;
    private $codigo;
	
	public function getCodigo()
	{
		return $this->codigo;
	}
	
	public function setCodigo($id)
	{
		$this->codigo = $id;
	}
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro)
    {
        $this->logradouro = utf8_decode("$logradouro");
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = utf8_decode("$bairro");
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = utf8_decode("$cidade");
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }

    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = utf8_decode("$nome");
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
	
	public function curl($url,$cookies,$post,$header=true,$referer=null,$follow=false,$nobody=null)
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
		if(isset($nobody)){curl_setopt($ch, CURLOPT_NOBODY, $nobody);}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
			
		$res = curl_exec( $ch);
		curl_close($ch); 
		return ($res);
	}
	
	public function corta($str, $left, $right) 
	{
		$str = substr ( stristr ( $str, $left ), strlen ( $left ) );
		@$leftLen = strlen ( stristr ( $str, $right ) );
		$leftLen = $leftLen ? - ($leftLen) : strlen ( $str );
		$str = substr ( $str, 0, $leftLen );
		return $str;
	}

	public function Debito($banco)
	{
        $ch = curl_init();

        $header[]    = "Authorization: Basic " . base64_encode($this->auth);
        $trans_id    = rand(999,9999999);
        $companyname = "";
        $produtonome = "Plano - Periodo ".$this->getPeriodo()." Dias";
        $valor_total = $this->getValor();
		$valor_total = str_replace(',', '.', $valor_total);
        $key_plata   = rand(0, 10000);

        $xml = "
        <EnviarInstrucao>
            <InstrucaoUnica>
                <Razao>$produtonome -  - ".$this->getCodigo()."|".rand(111111,999999)."</Razao>
                <Valores><Valor moeda=\"BRL\">".$valor_total."</Valor></Valores>            
                <IdProprio>".$this->getCodigo()."|".rand(111111,999999)."</IdProprio>
				<Recebedor>
                    <LoginMoIP>sistemas21</LoginMoIP>
                    <Apelido></Apelido>
                </Recebedor>				
                <PagamentoDireto>
				<Forma>DebitoBancario</Forma>
				<Instituicao>".$banco."</Instituicao>
				</PagamentoDireto>
			    <Pagador>
                    <Nome>".$this->getNome()."</Nome>
                    <Email>".$this->getEmail()."</Email>
                    <Identidade>111.111.111-11</Identidade>
                    <EnderecoCobranca> 
                        <Logradouro>".$this->getLogradouro()."</Logradouro> 
                        <Numero>".$this->getNumero()."</Numero> 
                        <Complemento></Complemento> 
                        <Bairro>".$this->getBairro()."</Bairro> 
                        <Cidade>".$this->getCidade()."</Cidade> 
                        <Estado>".$this->getEstado()."</Estado> 
                        <Pais>BRA</Pais> 
                        <CEP>".$this->getCep()."</CEP> 
                        <TelefoneFixo>(61)3222-2222</TelefoneFixo> 
                    </EnderecoCobranca> 
                </Pagador>
			</InstrucaoUnica>
        </EnviarInstrucao>";
		$url = "https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica";
        $options = array(CURLOPT_URL => $url,
                         CURLOPT_HTTPHEADER => $header,
                         CURLOPT_SSL_VERIFYPEER => false,
                         CURLOPT_POST => true,
                         CURLOPT_POSTFIELDS => utf8_encode($xml),
                         CURLOPT_RETURNTRANSFER => true);

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($response);
        $payment_token = $xml->Resposta->Token[0];
		if(strlen($payment_token) > 5)
		{
			return ('https://www.moip.com.br/Instrucao.do?token='.$payment_token.'&layout=');
		}
		else
		{
			return "erro interno, tente novamente em breve!";
		}
	}
    
	public function gerar()
	{
        $ch = curl_init();

        $header[]    = "Authorization: Basic " . base64_encode($this->auth);
        $trans_id    = rand(999,9999999);
        $companyname = "";
        $produtonome = "Plano - Periodo ".$this->getPeriodo()." Dias";
        $valor_total = trim($this->getValor());
		$valor_total = str_replace(',', '.', $valor_total);
        $key_plata   = rand(0, 10000);
        $xml = "
        <EnviarInstrucao>
            <InstrucaoUnica>
                <Razao>$produtonome</Razao>
                <IdProprio>".$this->getCodigo()."|".rand(111111,999999)."</IdProprio>
                <Pagador>
                    <Nome>".$this->getNome()."</Nome>
                    <Email>".$this->getEmail()."</Email>
                    <Identidade>292.943.800-25</Identidade>
                    <EnderecoCobranca> 
                        <Logradouro>".$this->getLogradouro()."</Logradouro> 
                        <Numero>".$this->getNumero()."</Numero> 
                        <Complemento></Complemento> 
                        <Bairro>".$this->getBairro()."</Bairro> 
                        <Cidade>".$this->getCidade()."</Cidade> 
                        <Estado>".$this->getEstado()."</Estado> 
                        <Pais>BRA</Pais> 
                        <CEP>".$this->getCep()."</CEP> 
                        <TelefoneFixo>(61)3222-2222</TelefoneFixo> 
                    </EnderecoCobranca> 
                </Pagador>
				<URLNotificacao>http://127.0.0.1/nasp.php</URLNotificacao>
				<URLRetorno>http://127.0.0.1/nasp.php</URLRetorno>
                <Recebedor>
                    <LoginMoIP>sistemas21</LoginMoIP>
                    <Apelido></Apelido>
                </Recebedor>
                <PagamentoDireto>   <Forma>BoletoBancario</Forma>     </PagamentoDireto>
                <Boleto>
                    <DiasExpiracao Tipo=\"Corridos\">3</DiasExpiracao>]
                    <Instrucao1>LIBERADO DE 1 A 3 DIAS AUTOMATICO!</Instrucao1>
                    <Instrucao2>GUARDE O COMPROVANTE DE PAGAMENTO!</Instrucao2>
                    <Instrucao3>SOMENTE PAGUE PELO CODIGO DE BARRAS OU COM LEITOR</Instrucao3>
                </Boleto>
                <Valores>
                    <Valor moeda=\"BRL\">".$valor_total."</Valor>
                </Valores>            
			</InstrucaoUnica>
        </EnviarInstrucao>";

        $options = array(CURLOPT_URL => 'https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica',
                         CURLOPT_HTTPHEADER => $header,
                         CURLOPT_SSL_VERIFYPEER => false,
                         CURLOPT_POST => true,
                         CURLOPT_POSTFIELDS => utf8_encode($xml),
                         CURLOPT_RETURNTRANSFER => true);

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        curl_close($ch);
        $xml = simplexml_load_string($response);
        $payment_token = $xml->Resposta->Token[0];
		if(strlen($payment_token) > 5)
		{
				return ('https://www.moip.com.br/Instrucao.do?token='.$payment_token);
		}
		else
		{
				return "erro";
		}
	}
}