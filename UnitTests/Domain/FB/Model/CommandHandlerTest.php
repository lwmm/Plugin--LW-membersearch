<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__) . '/../../../../../lw_ddd/commandLogsHandler.php';
require_once dirname(__FILE__) . '/../../../../Domain/FB/Model/CommandHandler.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

/**
 * Test class for CommandHandler.
 * Generated by PHPUnit on 2013-01-22 at 14:23:07.
 */
class CommandHandlerTest_fb extends \PHPUnit_Framework_TestCase {

    /**
     * @var CommandHandler
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
        
        $this->commandHandler = new \lwMembersearch\Domain\FB\Model\CommandHandler($this->db);
        $this->assertTrue($this->createTable());
        $this->assertTrue($this->createLogTable());
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

    public function testAddEntity()
    {
        // @param array
        // check if add entity works with a full valid array
        
        $this->addEntry();
    }
    
    public function testSaveEntity()
    {
        // @param id, array
        // add entry 
        // check if save entity works with a full valid array
        
        $id = $this->addEntry();
        
        $array = array(
            "lw_object"     => "lw_membersearch_fb",
            "category_id"   => 1,
            "name"          => "Metatexx",
            "opt1text"      => "MT"
        );
        
        $this->assertTrue($this->commandHandler->saveEntity($id,$array));
        $this->db->setStatement("SELECT * FROM t:lw_master WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $result = $this->db->pselect1();
        
        $assertedArray = array();
        foreach($result as $key => $value){
            if($key != "lw_object" & $key != "name" & $key != "category_id" & $key != "opt1text"){
                unset($key);
            }else{
                $assertedArray[$key] = $value;
            }
        }
        
        $this->assertEquals($assertedArray, $array);
    }
    
    public function testDeleteEntity()
    {
        // @param id
        // add entry
        // check if delete entry works
        // existing / non existing id
        
        $id = $this->addEntry();
        $this->assertTrue($this->commandHandler->deleteEntityById($id));
        $this->db->setStatement("SELECT * FROM t:lw_master WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->assertEmpty($this->db->pselect1());
        
        $this->assertTrue($this->commandHandler->deleteEntityById(9999));
        $this->db->setStatement("SELECT * FROM t:lw_master WHERE id = 9999 ");
        $this->assertEmpty($this->db->pselect1());
    }
    
    public function addEntry()
    {
        $array = array(
            "lw_object"     => "lw_membersearch_fb",
            "category_id"   => 1,
            "name"          => "LogicWorks",
            "opt1text"      => "LW"
        );
        
        $insert_id = $this->commandHandler->addEntity($array);
        
        $this->db->setStatement("SELECT * FROM t:lw_master WHERE id = :id ");
        $this->db->bindParameter("id", "i", $insert_id);
        $result = $this->db->pselect1();
        
        $assertedArray = array();
        foreach($result as $key => $value){
            if($key != "lw_object" & $key != "name" & $key != "category_id" & $key != "opt1text"){
                unset($key);
            }else{
                $assertedArray[$key] = $value;                
            }
        }
        
        $this->assertEquals($assertedArray, $array);
        
        return $insert_id;
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
                                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ; ");
        
        return $this->db->pdbquery();
    }
    
    public function createLogTable()
    {
        $this->db->setStatement("CREATE TABLE IF NOT EXISTS lw_command_log (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  project varchar(255) NOT NULL,
                                  domain varchar(22) NOT NULL,
                                  statement longtext NOT NULL,
                                  date varchar(20) NOT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ; ");
        return $this->db->pdbquery();
    }
}