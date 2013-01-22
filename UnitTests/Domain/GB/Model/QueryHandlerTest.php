<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__) . '/../../../../Domain/GB/Model/QueryHandler.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

/**
 * Test class for QueryHandler.
 * Generated by PHPUnit on 2013-01-22 at 14:57:32.
 */
class QueryHandlerTest extends \PHPUnit_Framework_TestCase 
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
        
        $this->queryHandler = new \lwMembersearch\Domain\GB\Model\QueryHandler($this->db);
        $this->assertTrue($this->createTable());
        // fill table
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
    }
    
    public function loadObjectById()
    {
        //@param id
        // check if correct entries are returned
        // exsiting / non existing id
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
}