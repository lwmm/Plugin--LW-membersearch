<?php

namespace lwMembersearch\Domain\Member\Model;

class memberQueryHandler
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * A member with certain id and status ( intern ) will be loaded
     * @param int $id
     * @param bool $intern
     * @return array
     */
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
    
    /**
     * All member will be loaded if intern = true,
     * All non intern members will be loaded if intern = false
     * @param bool $intern
     * @return array
     */
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
    
    /**
     * All member with certain department will be loaded if intern = true,
     * All non intern members with certain department will be loaded if intern = false
     * @param int $department
     * @param bool $intern
     * @return array
     */
    public function loadMembersByDepartment($department, $intern=false)
    {
        if(!$intern){
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE department = :department AND intern = :intern ");
            $this->db->bindParameter("department", "i", $department);
            $this->db->bindParameter("intern", "i", 0);
        }else{
            $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE department = :department ");
            $this->db->bindParameter("department", "i", $department);
        }
        
        return $this->db->pselect();
    }
    
    /**
     * All member with certain firstname and status will be loaded
     * @param string $name
     * @param bool $intern
     * @return array
     */
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

    /**
     * All member with certain location and status will be loaded
     * @param string $location
     * @param bool $intern
     * @return array
     */
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

    /**
     * All member which fits to the filterArray and status will be loaded
     * @param array $filterArray
     * @param bool $intern
     * @return array
     */
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
