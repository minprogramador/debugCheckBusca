<?php

class Sistema_Email extends Sistema_Db_Abstract
{
    protected $_table   = "email";
    private   $host     = null;
    private   $porta    = null;
    private   $usuario  = null;
    private   $senha    = null;
    private   $titulo   = null;
    private   $mensagem = null;
    private   $rEmail   = null;
    private   $rNome    = null;
    private   $status  = null;
    
    
    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getPorta()
    {
        return $this->porta;
    }

    public function setPorta($porta)
    {
        $this->porta = $porta;
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
    
    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }
    
    public function getREmail()
    {
        return $this->rEmail;
    }

    public function setREmail($rEmail)
    {
        $this->rEmail = $rEmail;
    }

    public function getRNome()
    {
        return $this->rNome;
    }

    public function setRNome($rNome)
    {
        $this->rNome = $rNome;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
        
    protected function _insert(){}
    
    protected function _update()
    {
        $db = $this->getDb();
        $stm = $db->prepare(" update $this->_table set host=:host, porta=:porta, usuario=:usuario, senha=:senha, status=:status where id=:id");
       
        $stm->bindValue(':id', '1');
        $stm->bindValue(':host',$this->getHost());
        $stm->bindValue(':porta',$this->getPorta());
        $stm->bindValue(':usuario',$this->getUsuario());
        $stm->bindValue(':senha',$this->getSenha());
        $stm->bindValue(':status',$this->getStatus());
        
        return $stm->execute();
    }
   
    public function Enviar()
    {
        $db1  = $this->getDb();
        $stm1 = $db1->prepare("select * from $this->_table where id=:id");
        $stm1->bindValue(':id', 1);
        $stm1->execute();
        $res = $stm1->fetch(PDO::FETCH_ASSOC);    
        #verifica se ta ok para enviar mensagens.
        if($res['status'] == 2)
        {
            return false;
            die;
        }
        
        $mail = new Sistema_PHPMailer();

        $mail->IsSMTP();
        $mail->Host = $res['host'];
        $mail->Port = $res['porta'];
        $mail->SMTPAuth = true;
        $mail->Username = $res['usuario'];
        $mail->Password = $res['senha'];

        $res['usuario']  = str_replace('+', '@', $res['usuario']);
        $mail->From      = $res['usuario'];
        $mail->FromName  = NOMESITE;

        # Define os destinatario
        $mail->AddAddress($this->getREmail(), $this->getRNome());
        $mail->IsHTML(true);

        #Define a mensagem (Titulo e Mensagem)
        $mail->Subject  = utf8_decode($this->getTitulo());
        $mail->Body     = utf8_decode($this->getMensagem());
        $mail->AltBody  = utf8_decode($this->getMensagem());

        $enviado = $mail->Send();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();

        if ($enviado)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}