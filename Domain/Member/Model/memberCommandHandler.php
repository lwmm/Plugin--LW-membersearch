<?php
namespace lwMembersearch\Domain\Member\Model;
use \LWddd\commandLogsHandler as commandLogsHandler;

class memberCommandHandler
{
    public function __construct($db)
    {
        $this->db = $db;
        $this->commandLogsHandler = new commandLogsHandler($db);
        $this->logArray = array(
            "project"   => "lwMembersearch",
            "domain"    => "Member",
            "statement" => ""
        );
    }
    
    /**
     * Creation of lw_membersearch table
     * @return boolean
     * @throws Exception
     */
    public function createMemberTable()
    {
        $create_statement = "CREATE TABLE IF NOT EXISTS lw_membersearch (
                              id int(11) NOT NULL AUTO_INCREMENT,
                              firstname varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              lastname varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              email varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              building varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              room varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              phone varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              fax varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              location varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                              department int(11),
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
    
    /**
     * A new member will be added
     * @param array $array
     * @return boolean
     * @throws \Exception
     */
    public function addMember($array)
    {
        $this->db->setStatement("INSERT INTO t:lw_membersearch ( firstname, lastname, email, building, room, phone, fax, location, department, intern, lw_first_date, lw_last_date ) VALUES ( :firstname, :lastname, :email, :building, :room, :phone, :fax, :location, :department, :intern, :lw_first_date, :lw_last_date ) ");
        $this->db->bindParameter("firstname", "s", $array["firstname"]);
        $this->db->bindParameter("lastname", "s", $array["lastname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("building", "s", $array["building"]);
        $this->db->bindParameter("room", "s", $array["room"]);
        $this->db->bindParameter("phone", "s", $array["phone"]);
        $this->db->bindParameter("fax", "s", $array["fax"]);
        $this->db->bindParameter("location", "s", $array["location"]);
        $this->db->bindParameter("intern", "i", $array["intern"]);
        $this->db->bindParameter("department", "i", $array["department"]);
        $this->db->bindParameter("lw_first_date", "i", date("YmdHis"));
        $this->db->bindParameter("lw_last_date", "i", date("YmdHis"));

        $this->logArray["statement"] = $this->db->prepare();
        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "addMemer"');
        }
        $this->commandLogsHandler->addLog($this->logArray);
        return true;    
    }
    
    /**
     * A member with certain id will be updated
     * @param int $id
     * @param array $array
     * @return boolean
     * @throws \Exception
     */
    public function saveMember($id, $array)
    {
        $this->db->setStatement("UPDATE t:lw_membersearch SET firstname = :firstname, lastname = :lastname, email = :email, building = :building, room = :room, phone = :phone, fax = :fax, location = :location, department = :department, intern = :intern, lw_last_date = :lw_last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("firstname", "s", $array["firstname"]);
        $this->db->bindParameter("lastname", "s", $array["lastname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("building", "s", $array["building"]);
        $this->db->bindParameter("room", "s", $array["room"]);
        $this->db->bindParameter("phone", "s", $array["phone"]);
        $this->db->bindParameter("fax", "s", $array["fax"]);
        $this->db->bindParameter("location", "s", $array["location"]);
        $this->db->bindParameter("intern", "i", $array["intern"]);
        $this->db->bindParameter("department", "i", $array["department"]);
        $this->db->bindParameter("lw_last_date", "i", date("YmdHis"));

        $this->logArray["statement"] = $this->db->prepare();
        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "saveMemer"');
        }
        $this->commandLogsHandler->addLog($this->logArray);
        return true;    
    }
    
    /**
     * A member with certain id will be deleted
     * @param int $id
     * @return boolean
     * @throws \Exception
     */
    public function deleteMember($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_membersearch WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);

        $this->logArray["statement"] = $this->db->prepare();
        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception('ERROR: "deleteMemer"');
        }
        $this->commandLogsHandler->addLog($this->logArray);
        return true;    
    }
}
