<?php

class Sistema_Auth_Adapter_Db extends Sistema_Auth_Adapter_Abstract
{
    private $db_user     = null;
    private $db_password = null;
    private $table       = null;
    private $db          = null;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getDb_user()
    {
        return $this->db_user;
    }
    public function setDb_user($db_user)
    {
        $this->db_user = $db_user;
    }
    public function getDb_password()
    {
        return $this->db_password;
    }
    public function setDb_password($db_password)
    {
        $this->db_password = $db_password;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function setTable($table)
    {
        $this->table = $table;
    }
    public function getDb()
    {
        return $this->db;
    }
    public function setDb($db)
    {
        $this->db = $db;
    }
    public function autenticate()
    {
        $stm = $this->db->prepare("select * from $this->table where $this->db_user=:user and $this->db_password=:password");
        $stm->bindValue(':user',     $this->user);
        $stm->bindValue(':password', $this->password);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if (count($result) > 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}