<?php

abstract class Sistema_Db_Abstract
{
    protected $id     = null;
    protected $_table = null;
    public function getId()
    {
        return $this->id;
    }
    public function __construct(array $options = null)
    {
        if (isset($options) && count($options)){
            $this->setOptions($options);
        }
    }
    public function setOptions(array $options) 
    {
        $util = new Sistema_Util();
        $methods = get_class_methods($this);
        foreach ($options as $key => $value)
        {
            $method = 'set' . ucfirst($util->xss($key));
            if (in_array($method, $methods))
                $this->$method($util->xss($value));
        }
        return $this;
    }
    public function setId($id)
    {
        if(!is_null($this->id))
            throw new Exception ('O id nao pode ser alterado');
        $this->id = (int) $id;
    }
    public function fetchAll()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' select * from '.$this->_table);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' select * from '.$this->_table.' where id=:id');
        $stm->bindValue(':id',$this->getId());
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    public function delete()
    {
        $db  = $this->getDb();
        $stm = $db->prepare(' delete from '.$this->_table.' where id=:id');
        $stm->bindValue(':id',$this->getId());
        return $stm->execute();
    }
  
    abstract protected function _insert();
    abstract protected function _update();
    public function save()
    {
        if(is_null($this->getId()))
        {
            $res = $this->_insert();
            if($res)
            {
                $this->setId($this->getDb()->lastInsertId());
                return $this->getId();  
            }
        }
        else
        {
            return $this->_update();
        }
    }
  
    public function getDb()
    {
        global $config;
        return Sistema_Db_Connection::factory($config);
    }
}
