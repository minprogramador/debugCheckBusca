<?php

class Sistema_Paginacao extends Sistema_Db_Abstract
{
    public  $_table  = null;
    private $pagina  = null;
    private $limite  = null;
    private $total   = null;
    private $maximo  = null;
    private $base    = null;
    private $patch   = null;
    private $control = null;
    
    public function getTable()
    {
        return $this->_table;
    }

    public function setTable($_table)
    {
        $this->_table = $_table;
    }

    public function getPagina()
    {
        return $this->pagina;
    }

    public function setPagina($pagina)
    {
        $this->pagina = $pagina;
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        $this->limite = $limite;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }
    
    public function getMaximo()
    {
        return $this->maximo;
    }

    public function setMaximo($maximo)
    {
        $this->maximo = $maximo;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function setBase($base)
    {
        $this->base = $base;
    }
    
    public function getPatch()
    {
        return $this->patch;
    }

    public function setPatch($patch)
    {
        $this->patch = $patch;
    }

    public function getControl()
    {
        return $this->control;
    }

    public function setControl($control)
    {
        $this->control = $control;
    }

    public function getCount()
    {
        $db  = $this->getDb();
        $stm = $db->prepare("select * from ".$this->_table .' GROUP BY id HAVING COUNT(*) > 0');
        $stm->execute();
        return $stm->rowCount();  
    }
    
    public function getDados($where=null)
    {
        if(isset($where))
        {
            $usuario = $where;
            $where   = " where usuario=:usuario ";
        }
        else
        {
            $where = ' GROUP BY id HAVING COUNT(*) > 0 LIMIT '.$this->getLimite().','.$this->getMaximo();
        }
        
        $db  = $this->getDb();
        $stm = $db->prepare("select * from ".$this->_table.$where);
        
        if(isset($where)){$stm->bindValue(':usuario', $usuario);}
        
        $stm->execute();
		
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDadosIdUser($where=null)
    {
        if(isset($where))
        {
            $usuario = $where;
            $where   = " where id_usuario=:usuario ";
        }
        else
        {
            $where = ' GROUP BY id HAVING COUNT(*) > 0 LIMIT '.$this->getLimite().','.$this->getMaximo();
        }
        
        $db  = $this->getDb();
        $stm = $db->prepare("select * from ".$this->_table.$where);
        
        if(isset($where)){$stm->bindValue(':usuario', $usuario);}
        
        $stm->execute();
        
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
	
	
	public function getDadosWhere($where)
    {
        if(isset($where))
        {
            $usuario = $where;
           # $where   = " where usuario=:usuario ";
        }
        else
        {
            $where = ' GROUP BY id HAVING COUNT(*) > 0 LIMIT '.$this->getLimite().','.$this->getMaximo();
        }
        
        $db  = $this->getDb();
        $stm = $db->prepare("select * from ".$this->_table.$where);
        
        if(isset($where)){$stm->bindValue(':usuario', $usuario);}
        
        $stm->execute();
		
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getDadosUsuarios($ordenar=null,$busca=null)
    {
        if(!isset($ordenar))
        {
            $ord = "GROUP BY id HAVING COUNT(*) > 0 LIMIT ".$this->getLimite().','.$this->getMaximo();
        }
        else
        {
            $ord = "where usuarios.status=".$ordenar;
        }
        
        if(isset($busca))
        {
            $ord = "where usuarios.nome LIKE '%$busca%' OR usuarios.email LIKE '%$busca%' OR usuarios.usuario LIKE '%$busca%' ";
        }

        $db  = $this->getDb();
        $stm = $db->prepare("select `usuarios`.*, `planos`.contratacao,`planos`.vencimento,`planos`.limite,`planos`.usado,`planos`.valor FROM `usuarios` INNER JOIN `planos` ON `usuarios`.`id` = `planos`.`id_usuario` $ord ");
        
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function _insert(){}
    protected function _update(){}

    public function verificaPagina()
    {
        if(isset($_REQUEST[$this->getPatch()]))
        {
            if (intval($_REQUEST[$this->getPatch()])>0)
            {
                return intval($_REQUEST[$this->getPatch()]);
            }
            else
            {
            	return false;
            }
        }
	return false;
    }
   
    public function getProximaPg()
    {
        if(($var= $this->verificaPagina()))
        {
            return $var;
        }
        else
        {
            return 1;
        }
    }
   
    public function doPagina()
    {
        $index_limit  = $this->getBase();
    	$query        = '';
        $page_size    = $this->getBase();
        $thepage      = $this->getPagina();
        $query_string = null;
        $total        = $this->getTotal();
       
        if(strlen($query_string)>0)
        {
            $query = "&amp;".$query_string;
        }
      
        $current = $this->getProximaPg();
        $total_pages=ceil($total/$page_size);
    	$start = max($current-intval($index_limit/2), 1);
	$end   = $start+$index_limit-1;

	$controlers = '<div class="dataTables_paginate paging_full_numbers">';
        
        if($current==1)
	{
            $controlers .= '<span class="previous paginate_button paginate_button_disabled">Anterior</span>';
        }
        else
        {
            $i = $current-1;
            $controlers .= '<span class="previous paginate_button paginate_button_disabled"><a href="'.$thepage.'/'.$i.$query.'">Anterior</a></span>';
        }

        if($start > 1)
        {
            $i = 1;
            $controlers .= '<a href="'.$thepage.'/'.$i.$query.'" title="Ir para a pagina '.$i.'">'.$i.'</a>&nbsp;';
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++)
        {
            if($i==$current)
            {
                $controlers .= '<span class="paginate_active">'.$i.'</span>';
            }
            else 
            {
                $controlers .= '<span class="paginate_button"><a href="'.$thepage.'/'.$i.$query.'">'.$i.'</a></span>';
            }
        }
        
        if($total_pages > $end)
        {
            $i = $total_pages;
            $controlers .= '<span class="paginate_button"><a href="'.$thepage.'/'.$i.$query.'">'.$i.'</a></span>';
        }
        
        if($current < $total_pages)
        {
            $i = $current+1;
            $controlers .= '<span class="next paginate_button"><a href="'.$thepage.'/'.$i.$query.'">Proxima</a></span>';
        }
    	else 
    	{
            $controlers .= '<span class="next paginate_button paginate_button_disabled">Proxima</span>';
	}
    
	if ($total != 0)
	{
            $controlers .= '</div>';
        }
        $this->setControl($controlers);
    }


    public function getDadosFaturas1($idFatura=null,$idTrans=null,$tipo=null,$formaPg=null,$status=null,$datai=null,$dataf=null)
    {
        $ord = '';
        $qnt = '0';

        if(strlen($idFatura) > 0)
        {
            $qnt    = $qnt + 1; $ord .= ' f.id="'.$idFatura.'"';
        }

        if(strlen($idTrans) > 0)
        {
            if($qnt > 0){ $ord .= ' and f.id_fatura="'.$idTrans.'"'; }else{ $ord .= ' f.id_fatura="'.$idTrans.'"'; }
            $qnt = $qnt + 1;
        }

        if(strlen($tipo) > 0)
        {
            if($qnt > 0){ $ord .= ' and f.tipo="'.$tipo.'" '; }else{ $ord .= ' f.tipo="'.$tipo.'"'; }
            $qnt = $qnt + 1;
        }

        if(strlen($formaPg) > 0)
        {
            if($qnt > 0)
            {
                if(stristr($formaPg, ','))
                {
                    $ord .= 'and (f.forma_pagamento="2" OR f.forma_pagamento="9") ';
                }
                else
                {
                    $ord .= ' and f.forma_pagamento="'.$formaPg.'" ';
                }
            }
            else
            {
                if(stristr($formaPg, ','))
                {
                    $ord .= ' (f.forma_pagamento="2" OR f.forma_pagamento="9") ';
                }
                else
                {
                    $ord .= ' f.forma_pagamento="'.$formaPg.'"';
                }
            }

            $qnt = $qnt + 1;
        }

        if(strlen($status) > 0)
        {
            if($qnt > 0)
            {
                if($status == '3'){ $ord .= ' and f.status IN (2,3) group by f.id';}
                elseif($status == '1'){ $ord .= ' and f.status IN (1,4) '; }
                else{ $ord .= ' and f.status="'.$status.'"'; }
            }
            else
            {
                if($status == '3'){ $ord .= ' f.status IN (2,3) ';}
                elseif($status == '1'){ $ord .= ' f.status IN (1,4) '; }
                else{ $ord .= ' f.status="'.$status.'"  '; }
            }
            $qnt = $qnt + 1;
        }

        if(strlen($dataf) < 3){ $dataf = $datai; }else{ $dataf = $dataf; }

        if(strlen($datai) > 0)
        {
            $datai = $datai;
            if($qnt > 0){ $ord .= ' and (f.data BETWEEN "'.$datai.'" AND "'.$dataf.'") group by f.id'; }else{ $ord .= ' (f.data BETWEEN "'.$datai.'" AND "'.$dataf.'")'; }
            $qnt = $qnt + 1;
        }

        if(strlen($ord) < 1)
        {
            $ord  = "group by f.id ORDER BY f.data DESC LIMIT ".$this->getLimite().','.$this->getMaximo();
            $sqll = "select f.id,f.id_usuario,f.data,f.data_pagamento,f.data_vencimento,f.periodo,f.valor,f.forma_pagamento,f.tipo,f.status,f.status_fatura,f.id_fatura, u.usuario as usuario from fatura as f LEFT JOIN usuarios as u ON f.id_usuario = u.id ".$ord;
        }
        else
        {
            if(!stristr($ord, 'group by f.id'))
            {
                $ord = $ord.' group by f.id';
            }

            $sqll = "select f.id,f.id_usuario,f.data,f.data_pagamento,f.data_vencimento,f.periodo,f.valor,f.forma_pagamento,f.tipo,f.status,f.status_fatura,f.id_fatura, u.usuario as usuario from fatura as f LEFT JOIN usuarios as u ON f.id_usuario = u.id where ".$ord;
        }
        #die($sqll);
        $db  = $this->getDb();
        $stm = $db->prepare($sqll);
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getValorFaturas1($idFatura=null,$idTrans=null,$tipo=null,$formaPg=null,$status=null,$datai=null,$dataf=null)
    {
        $ord = '';
        $qnt = '0';

        if(strlen($idFatura) > 0)
        {
            $qnt    = $qnt + 1; $ord .= ' fatura.id="'.$idFatura.'" ';
        }

        if(strlen($idTrans) > 0)
        {
            if($qnt > 0){ $ord .= ' and fatura.id_fatura="'.$idTrans.'"'; }else{ $ord .= ' fatura.id_fatura="'.$idTrans.'" '; }
            $qnt = $qnt + 1;
        }

        if(strlen($tipo) > 0)
        {
            if($qnt > 0){ $ord .= ' and fatura.tipo="'.$tipo.'" '; }else{ $ord .= ' fatura.tipo="'.$tipo.'" '; }
            $qnt = $qnt + 1;
        }

        if(strlen($formaPg) > 0)
        {
            if($qnt > 0)
            {
                if(stristr($formaPg, ','))
                {
                    $ord .= 'and (fatura.forma_pagamento="2" OR fatura.forma_pagamento="9") ';
                }
                else
                {
                    $ord .= ' and fatura.forma_pagamento="'.$formaPg.'" ';
                }
            }
            else
            {
                if(stristr($formaPg, ','))
                {
                    $ord .= ' (fatura.forma_pagamento="2" OR fatura.forma_pagamento="9") ';
                }
                else
                {                
                    $ord .= ' fatura.forma_pagamento="'.$formaPg.'" ';
                }
            }

            $qnt = $qnt + 1;

        }

        if(strlen($status) > 0)
        {
            if($qnt > 0)
            {
                if($status == '3'){ $ord .= ' and fatura.status IN (2,3) ';}
                elseif($status == '1'){ $ord .= ' and fatura.status IN (1,4) '; }
                else{ $ord .= ' and fatura.status="'.$status.'" '; }
            }
            else
            {
                if($status == '3'){ $ord .= ' fatura.status IN (2,3) ';}
                elseif($status == '1'){ $ord .= ' fatura.status IN (1,4) '; }
                else{ $ord .= ' fatura.status="'.$status.'" '; }
            }
            $qnt = $qnt + 1;
        }

        if(strlen($dataf) < 3){ $dataf = $datai; }else{ $dataf = $dataf; }

        if(strlen($datai) > 0)
        {
            $datai = $datai;
            if($qnt > 0){ $ord .= ' and (fatura.data BETWEEN "'.$datai.'" AND "'.$dataf.'") '; }else{ $ord .= ' (fatura.data BETWEEN "'.$datai.'" AND "'.$dataf.'") '; }
            $qnt = $qnt + 1;
        }

        if(strlen($ord) < 1)
        {
            $ord  = "ORDER BY fatura.data DESC LIMIT ".$this->getLimite().','.$this->getMaximo();
            $sqll = "select `fatura`.*,SUM(fatura.valor) as ValorTotal,`usuarios`.usuario FROM `fatura` INNER JOIN `usuarios` ON `usuarios`.`id` = `fatura`.`id_usuario` ".$ord;
        }
        else
        {
            $ord  = $ord;
            $sqll = "select `fatura`.*,SUM(fatura.valor) as ValorTotal,`usuarios`.usuario FROM `fatura` INNER JOIN `usuarios` ON `usuarios`.`id` = `fatura`.`id_usuario` where ".$ord;
        }
#        die($sqll);

        $db  = $this->getDb();
        $stm = $db->prepare($sqll);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }



}