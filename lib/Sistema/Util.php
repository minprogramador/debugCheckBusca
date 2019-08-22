<?php

class Sistema_Util extends Sistema_Db_Abstract
{
    public function curl($url,$cookies,$post,$header=true,$referer=null,$follow=false)
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
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
	
	$res = curl_exec( $ch);
	curl_close($ch); 
	#return utf8_decode($res);
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

    public function getCookies($get)
    {
        preg_match_all('/Set-Cookie: (.*);/U',$get,$temp);
	$cookie = $temp[1];
	$cookies = implode('; ',$cookie);
	return $cookies;
    }
             
    public function xss($data, $problem='')
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);

        if ($problem && strlen($data) == 0)
        {
            return ($problem);
        }
        return $data;
    }
    
    public function Convert($res)
    {
        $res = preg_replace("/[^a-zA-Z0-9.]/", "", strtr($res, "áàãâéêíóôõúüçñÁÀÃÂÉÊÍÓÔÕÚÜÇÑ ", "aaaaeeiooouucnAAAAEEIOOOUUCN_"));
        return $res;
    }
    
    public function DelAcento($string)
    {
        $a = 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏàáâãäåçèéêëìíîïñòóôõöøùúû';
        $b = 'aaaaaaceeeeiiiiaaaaaaceeeeiiiinoooooouuu';
        $string = strtr($string, utf8_decode($a), $b);
        return utf8_encode($string);
    }
    
    public function UrlPatch()
    {
        if($_SERVER['HTTP_HOST'])
        {
            if($_SERVER['REQUEST_URI'])
            {
                $url = $_SERVER['HTTP_HOST'].($_SERVER['REQUEST_URI']);
                $url = explode('/',$url);
                $url = 'http://'.$url[0].'/'.$url[1];
                
                if(strlen($url[1]) < 2)
                {
                    $url = $_SERVER['HTTP_HOST'];
                    $url = explode('/',$url);
                    $url = 'http://'.$url[0];
                }
            }
            else
            {
                $url = $_SERVER['HTTP_HOST'];
                $url = explode('/',$url);
                $url = 'http://'.$url[0];

            }
        }
        return $url;
    }
    
    public function Msg($width=null,$tipo='erro',$titulo=null,$mensagem=null)
    {
        #tipo = ( erro || sucesso )
        if(isset($width))
        {
            $width = "style=\"width:$width;\"";
        }
        
        if(isset($titulo))
        {
            $titulo = "<strong>$titulo</strong><br>";
        }
       
        return "<center><div $width class=\"Mensagem $tipo\"> $titulo $mensagem</div></center>";
    }
    
  
    public function conData($data)
    {
        return implode(!strstr($data, '/') ? "/" : "-", array_reverse(explode(!strstr($data, '/') ? "-" : "/", $data)));      
    }
	
	public function countData($data)
	{
		$data_inicial = date("Y-m-d");
		$data_final   = $data;
		
		$time_inicial = strtotime($data_inicial);
		$time_final   = strtotime($data_final);		
		$diferenca    = $time_final - $time_inicial;
		
		$count = (int)floor( $diferenca / (60 * 60 * 24));
		return $count;
	}
    
	function ConDh($data)
	{
		return strftime("%d/%m/%Y %H:%M:%S", strtotime($data));
	} 

    public function ir($ir,$msg=null,$icon=null)
    {
        if(isset($msg))
        {
            $_SESSION['AlertMng'] = $msg;
            $_SESSION['IconAler'] = $icon;
        }
        header("Location:".$ir);
        die;
    }
    
    public function limpaTudo()
    {
        foreach( $_SESSION as $Index => $Data)
        {
            unset($_SESSION[$Index]);
        }

        foreach( $_COOKIE as $Index => $Data )
        {
            setcookie($Index, '', time()-172800);
        }
    }
    
    protected function _insert(){}
    protected function _update(){}
    
    public function getBrowser()
    {
        $u_agent  = $_SERVER['HTTP_USER_AGENT'];
        $bname    = 'Unknown';
        $platform = 'Unknown';
        $version  = "";

        if (preg_match('/linux/i', $u_agent))
            {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent))
            {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent))
            {
            $platform = 'windows';
        }

        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        $known   = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches))
        {
                    //
        }

        $i = count($matches['browser']);
        if ($i != 1)
            {
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
                    {
                $version= $matches['version'][0];
            }
            else
                    {
                $version= $matches['version'][1];
            }
        }
        else
            {
            $version= $matches['version'][0];
        }

        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
    
    public function dec($get)
    {
	$conf = urldecode(base64_decode(urldecode(base64_decode(urlencode($get)))));
	return $conf;
    }
	
	public function Valcpf($cpf)
	{
		$s = $cpf;
		$c = substr($s, 0, 9);
		$dv = substr($s, 9, 2);
		$d1 = 0;
		$v = false;
	 
		for ($i = 0; $i < 9; $i++){$d1 = $d1 + substr($c, $i, 1) * (10 - $i);}
		if($d1 == 0)
		{
			return false;
			$v = true;
		}
		$d1 = 11 - ($d1 % 11);
		if($d1 > 9){$d1 = 0;}
		
		if(substr($dv, 0, 1) != $d1)
		{
			return false;
			$v = true;
		}
		
		$d1 = $d1 * 2;
		for ($i = 0; $i < 9; $i++){$d1 = $d1 + substr($c, $i, 1) * (11 - $i);}
		$d1 = 11 - ($d1 % 11);
		if($d1 > 9){$d1 = 0;}
		
		if(substr($dv, 1, 1) != $d1)
		{
			return false;
			$v = true;
		}
		if(!$v){return true;}
	}
	
    public function parseForm($data)
    {
        $post = array();
        if(preg_match_all('/<input(.*)>/U', $data, $matches))
        {
            foreach($matches[0] as $input)
            {
                if(!stristr($input, "name=")) continue;
                if(preg_match('/name=(".*"|\'.*\')/U', $input, $name))
                {
                    $key = substr($name[1], 1, -1);
                    if(preg_match('/value=(".*"|\'.*\')/U', $input, $value)) $post[$key] = substr($value[1], 1, -1);
                    else $post[$key] = "";
                }
            }
        }
        return $post;
    }
}