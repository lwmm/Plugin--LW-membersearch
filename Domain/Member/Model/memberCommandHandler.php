<?php
namespace lwMembersearch\Domain\Member\Model;

class memberCommandHandler
{
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function createMemberTable()
    {
        $create_statement = "CREATE TABLE IF NOT EXISTS lw_membersearch (
                              id bigint(11) NOT NULL AUTO_INCREMENT,
                              firstname varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              lastname varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              email varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              location varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                              department varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                              intern int(1) NOT NULL,
                              lw_first_date bigint(14) NOT NULL,
                              lw_last_date bigint(14) NOT NULL,
                              PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
        
        if(!$this->db->tableExists($this->db->gt("lw_membersearch"))){
            $this->db->setStatement($create_statement);
            $ok = $this->db->pdbquery();
            if(!$ok){
                throw new Exception('ERROR: Tablecreation "lw_membersearch" '); 
            }else{
                return true;
            }
        }
        return true;
    }
    
    public function addMember($array)
    {
        $this->db->setStatement("INSERT INTO t:lw_membersearch ( firstname, lastname, email, location, department, intern, lw_first_date, lw_last_date ) VALUES ( :firstname, :lastname, :email, :location, :department, :intern, :lw_first_date, :lw_last_date ) ");
        $this->db->bindParameter("firstname", "s", $array["firstname"]);
        $this->db->bindParameter("lastname", "s", $array["lastname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("location", "s", $array["location"]);
        $this->db->bindParameter("intern", "i", $array["intern"]);
        $this->db->bindParameter("department", "s", $array["department"]);
        $this->db->bindParameter("lw_first_date", "i", date("YmdHis"));
        $this->db->bindParameter("lw_last_date", "i", date("YmdHis"));

        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "addMemer"');
        }
        return true;    
    }
    
    public function saveMember($id, $array)
    {
        $this->db->setStatement("UPDATE t:lw_membersearch SET firstname = :firstname, lastname = :lastname, email = :email, location = :location, department = :department, intern = :intern, lw_last_date = :lw_last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("firstname", "s", $array["firstname"]);
        $this->db->bindParameter("lastname", "s", $array["lastname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("location", "s", $array["location"]);
        $this->db->bindParameter("intern", "i", $array["intern"]);
        $this->db->bindParameter("department", "s", $array["department"]);
        $this->db->bindParameter("lw_last_date", "i", date("YmdHis"));

        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "saveMemer"');
        }
        return true;    
    }
    
    public function deleteMember($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_membersearch WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);

        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "deleteMemer"');
        }
        return true;    
    }
}