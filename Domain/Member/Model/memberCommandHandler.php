<?php
namespace lwMembersearch\Domain\Member\Model;

require_once(dirname(__FILE__) . '/../Specification/isValid.php');
require_once(dirname(__FILE__) . '/../Specification/validationErrorsException.php');

use \lwMembersearch\Domain\Member\Specification\isValid as isValid;
use \lwMembersearch\Domain\Member\Specification\validationErrorsException as errorException;

class memberCommandHandler
{
    public function __construct($db)
    {
        $this->db = $db;
        $this->isValid = new isValid();
        $this->errorException = new errorException();
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
        if($this->isValid->validateMemberArray($array)){
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
                $this->errorException->errorThrowException(3);
            }
            return true;
        }
    }
    
    public function saveMember($id, $array)
    {
    }
    
    public function deleteMember($id)
    {
    }
}