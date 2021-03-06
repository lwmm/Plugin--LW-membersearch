<?php

namespace lwMembersearch\Domain\FB\Model;
use \LWddd\commandLogsHandler as commandLogsHandler;

class CommandHandler
{
    public function __construct($db)
    {
        $this->table = "lw_master";
        $this->db = $db;
        $this->type = "lw_membersearch_fb";
        $this->commandLogsHandler = new commandLogsHandler($db);
        $this->logArray = array(
            "project"   => "lwMembersearch",
            "domain"    => "FB",
            "statement" => ""
        );
    }
        
    /**
     * Creation of a new "Fachbereich"
     * @param array $array
     * @return true/exception
     */
    public function addEntity($array)
    {
        $this->db->setStatement("INSERT INTO t:".$this->table." ( lw_object, category_id, name, opt1text, lw_first_date, lw_last_date ) VALUES ( :lw_object, :category, :name, :shortcut, :first_date, :last_date ) ");
        $this->db->bindParameter("lw_object", "s", $this->type);
        $this->db->bindParameter("category", "s", $array['category_id']);
        $this->db->bindParameter("name", "s", $array['name']);
        $this->db->bindParameter("shortcut", "s", $array['opt1text']);
        $this->db->bindParameter("first_date", "s", date("YmdHis"));
        $this->db->bindParameter("last_date", "s", date("YmdHis"));

        $this->logArray["statement"] = $this->db->prepare();
        $id = $this->db->pdbinsert($this->table);
        if($id > 0) {
            $this->commandLogsHandler->addLog($this->logArray);
            return $id;
        }
        return false;
    }
    
    /**
     * A "Fachbereich" with certain id will be updated
     * @param int $id
     * @param array $array
     * @return true/exception
     */
    public function saveEntity($id, $array)
    {
        $this->db->setStatement("UPDATE t:".$this->table." SET name = :name, opt1text = :shortcut, lw_last_date = :last_date WHERE id = :id AND lw_object = :lw_object ");
        $this->db->bindParameter("lw_object", "s", $this->type);
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("name", "s", $array['name']);
        $this->db->bindParameter("shortcut", "s", $array['opt1text']);
        $this->db->bindParameter("last_date", "s", date("YmdHis"));
        
        $this->logArray["statement"] = $this->db->prepare();
        $ok =  $this->db->pdbquery();
        if($ok) {
            $this->commandLogsHandler->addLog($this->logArray);
            return true;
        }
        return false;
    }
    
    /**
     * A "Fachbereich" with certain id will be deleted
     * @param int $id
     * @return boolean
     */
    public function deleteEntityById($id)
    {
        $this->db->setStatement("DELETE FROM t:".$this->table." WHERE id = :id AND lw_object = :lw_object ");
        $this->db->bindParameter("lw_object", "s", $this->type);
        $this->db->bindParameter("id", "i", $id);
        
        $this->logArray["statement"] = $this->db->prepare();
        $ok =  $this->db->pdbquery();
        if($ok) {
            $this->commandLogsHandler->addLog($this->logArray);
            return true;
        }
        return false;
    }
}