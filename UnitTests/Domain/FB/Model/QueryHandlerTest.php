<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__) . '/../../../../Domain/FB/Model/QueryHandler.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

/**
 * Test class for QueryHandler.
 * Generated by PHPUnit on 2013-01-22 at 14:23:30.
 */
class QueryHandlerTest_fb extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var QueryHandler
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $phpUnitConfig = new phpUnitConfig();
        $config = $phpUnitConfig->getConfig();
        
        $db = new lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["db"]);
        $db->connect();
        $this->db = $db;
        
        $this->queryHandler =  new \lwMembersearch\Domain\FB\Model\QueryHandler($this->db);
        $this->assertTrue($this->createTable());
        $this->fillTable();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {
         $this->db->setStatement("DROP TABLE t:lw_master ");
         $this->db->pdbquery();
    }

    public function testLoadAllEntries()
    {
        //check if correct entries are returned
        $result = $this->queryHandler->loadAllEntries();
        $assertedArray = array();
        $i = 0;
        foreach($result as $entry){
            foreach($entry as $key => $value){
                if($key != "lw_object" & $key != "name" & $key != "category_id" & $key != "opt1text"){
                    unset($key);
                }else{
                    $assertedArray[$i][$key] = $value;                
                }
            }
            $i++;
        }
        
        $array = array(
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Logic",
                "opt1text" => "L"
            ),
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 2,
                "name" => "LogicWorks",
                "opt1text" => "LW"
            ),
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Works",
                "opt1text" => "W"
            )
                );
        
        $this->assertEquals($assertedArray, $array);
    }
    
    public function testloadAllEntriesByCategoryId()
    {
        //@param categoryid
        // check if correct entries are returned
        // exsiting / non existing id
        $result = $this->queryHandler->loadAllEntriesByCategoryId(1);
        $assertedArray = array();
        $i = 0;
        foreach($result as $entry){
            foreach($entry as $key => $value){
                if($key != "lw_object" & $key != "name" & $key != "category_id" & $key != "opt1text"){
                    unset($key);
                }else{
                    $assertedArray[$i][$key] = $value;                
                }
            }
            $i++;
        }
        
        $array = array(
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Logic",
                "opt1text" => "L"
            ),
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Works",
                "opt1text" => "W"
            )
                );
        
        $this->assertEquals($assertedArray, $array);
        
        $this->assertEmpty($this->queryHandler->loadAllEntriesByCategoryId(999));
    }
    
    public function testLoadObjectById()
    {
        //@param id
        // check if correct entries are returned
        // exsiting / non existing id
        $result = $this->queryHandler->loadObjectById(116);
        $assertedArray = array();
        foreach($result as $key => $value){
            if($key != "lw_object" & $key != "name" & $key != "category_id" & $key != "opt1text"){
                unset($key);
            }else{
                $assertedArray[$key] = $value;                
            }
        }
        
        $this->assertEquals($assertedArray, array(
                                                "lw_object" => "lw_membersearch_fb",
                                                "category_id" => 1,
                                                "name" => "Logic",
                                                "opt1text" => "L"
                                            ));
        
        $this->assertEmpty($this->queryHandler->loadObjectById(999));
    }
    
    public function createTable()
    {
        $this->db->setStatement("CREATE TABLE IF NOT EXISTS lw_master (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  lw_object varchar(30) DEFAULT NULL,
                                  name varchar(255) DEFAULT NULL,
                                  description varchar(255) DEFAULT NULL,
                                  category_id int(11) DEFAULT NULL,
                                  published int(1) DEFAULT NULL,
                                  disabled int(1) DEFAULT NULL,
                                  url varchar(255) DEFAULT NULL,
                                  language varchar(2) DEFAULT NULL,
                                  opt1bool int(1) DEFAULT NULL,
                                  opt2bool int(1) DEFAULT NULL,
                                  opt3bool int(1) DEFAULT NULL,
                                  opt4bool int(1) DEFAULT NULL,
                                  opt1number bigint(14) DEFAULT NULL,
                                  opt2number bigint(14) DEFAULT NULL,
                                  opt3number bigint(14) DEFAULT NULL,
                                  opt4number bigint(14) DEFAULT NULL,
                                  opt5number bigint(14) DEFAULT NULL,
                                  opt6number bigint(14) DEFAULT NULL,
                                  opt7number bigint(14) DEFAULT NULL,
                                  opt8number bigint(14) DEFAULT NULL,
                                  opt9number bigint(14) DEFAULT NULL,
                                  opt1text varchar(255) DEFAULT NULL,
                                  opt2text varchar(255) DEFAULT NULL,
                                  opt3text varchar(255) DEFAULT NULL,
                                  opt4text varchar(255) DEFAULT NULL,
                                  opt5text varchar(255) DEFAULT NULL,
                                  opt6text varchar(255) DEFAULT NULL,
                                  opt7text varchar(255) DEFAULT NULL,
                                  opt8text varchar(255) DEFAULT NULL,
                                  opt9text varchar(255) DEFAULT NULL,
                                  opt1file varchar(255) DEFAULT NULL,
                                  opt2file varchar(255) DEFAULT NULL,
                                  opt3file varchar(255) DEFAULT NULL,
                                  opt4file varchar(255) DEFAULT NULL,
                                  opt1clob longtext,
                                  opt2clob longtext,
                                  lw_first_date bigint(14) DEFAULT NULL,
                                  lw_first_user int(11) DEFAULT NULL,
                                  lw_last_date bigint(14) DEFAULT NULL,
                                  lw_last_user int(11) DEFAULT NULL,
                                  lw_version int(11) DEFAULT NULL,
                                  lw_removed int(11) DEFAULT NULL,
                                  lw_instance varchar(25) DEFAULT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=116 ; ");
        
        return $this->db->pdbquery();
    }
    
    public function fillTable()
    {
        $array = array(
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Logic",
                "opt1text" => "L"
            ),
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 2,
                "name" => "LogicWorks",
                "opt1text" => "LW"
            ),
            array(
                "lw_object" => "lw_membersearch_fb",
                "category_id" => 1,
                "name" => "Works",
                "opt1text" => "W"
            )
                );
        
        foreach($array as $entry){
            $this->db->setStatement("INSERT INTO t:lw_master ( lw_object, category_id, name, opt1text, lw_first_date, lw_last_date ) VALUES ( :lw_object, :category, :name, :shortcut, :first_date, :last_date ) ");
            $this->db->bindParameter("lw_object", "s", $entry["lw_object"]);
            $this->db->bindParameter("category", "s", $entry['category_id']);
            $this->db->bindParameter("name", "s", $entry['name']);
            $this->db->bindParameter("shortcut", "s", $entry['opt1text']);
            $this->db->bindParameter("first_date", "s", date("YmdHis"));
            $this->db->bindParameter("last_date", "s", date("YmdHis"));
            $this->db->pdbquery();
        }
    }
}