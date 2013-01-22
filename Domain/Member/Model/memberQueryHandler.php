<?php

namespace lwMembersearch\Domain\Member\Model;

class memberQueryHandler
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function loadMemberById($id, $intern=false)
    {
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = :id AND intern = :intern ");
        $this->db->bindParameter("id", "i", $id);
        if(!$intern){
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->bindParameter("intern", "i", 1);
        }
        return $this->db->pselect1();
    }
    
    public function loadAllMembers($intern=false)
    {
        if(!$intern){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE intern = :intern ");
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->setStatement("SELECT * FROM t:lw_membersearch ");
        }
        
        return $this->db->pselect();
    }
    
    public function loadMembersByDepartment($department, $intern=false)
    {
        if(!$intern){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE department = :department AND intern = :intern ");
            $this->db->bindParameter("department", "s", $department);
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE department = :department ");
            $this->db->bindParameter("department", "s", $department);
        }
        
        return $this->db->pselect();
    }
    
    public function loadMembersByName($name, $intern=false)
    {
        if(!$intern){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE firstname = :name AND intern = :intern ");
            $this->db->bindParameter("name", "s", $name);
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE firstname = :name ");
            $this->db->bindParameter("name", "s", $name);
        }
        
        return $this->db->pselect();
    }

    public function loadMembersByLocation($location, $intern=false)
    {
        if(!$intern){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE location = :location AND intern = :intern ");
            $this->db->bindParameter("location", "s", $location);
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE location = :location ");
            $this->db->bindParameter("location", "s", $location);
        }
        
        return $this->db->pselect();
    }

    public function loadMembersByFilter($filterArray, $intern=false)
    {    
         if(!$intern){
             $add_1 = " WHERE intern = 0 ";
             $add_2 = " intern = 0 AND ";
         }else{
             $add_2 = "";
         }
             
        if(empty($filterArray)){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch ".$add_1);
        }else{
            foreach($filterArray as $key => $value){
                $filterToSql.= " ".$key." = :".$key." AND";
            }
            $filterToSql = substr($filterToSql, 0, strlen($filterToSql) - 3);
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE ".$add_2.$filterToSql );
            foreach($filterArray as $key => $value){
                $this->db->bindParameter($key, "s", $value);
            }
        }

        return $this->db->pselect();
    }    
}