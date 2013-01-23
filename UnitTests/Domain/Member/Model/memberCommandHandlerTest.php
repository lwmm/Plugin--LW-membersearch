<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once(dirname(__FILE__) . '/../../../../Domain/Member/Model/memberCommandHandler.php');
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

class memberCommandHandlerTest extends \PHPUnit_Framework_TestCase {

    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        //CREATE TABLE
        $phpUnitConfig = new phpUnitConfig();
        $config = $phpUnitConfig->getConfig();
        
        $db = new lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["db"]);
        $db->connect();
        $this->db = $db;
   
        $this->memberCommandHandler = new lwMembersearch\Domain\Member\Model\memberCommandHandler($this->db);
        $this->assertTrue($this->memberCommandHandler->createMemberTable());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //DROP TABLE
        $this->db->setStatement("DROP TABLE t:lw_membersearch ");
        $this->db->pdbquery();
    }

    public function testCreateMemberTable()
    {
        //check if Table was created via SetUp
        /*
         * id: int(11), auto_increment, primary key
         * firstname: varchar(255)
         * lastname: varchar(255)
         * email: varchar(255)
         * location: varchar(20)
         * department: varchar(20)
         * intern: int(1)
         * lw_first_date: bigint(14)
         * lw_last_date: bigint(14)
         */
        $this->assertTrue($this->memberCommandHandler->createMemberTable());
        $this->assertTrue($this->db->tableExists($this->db->gt("lw_membersearch")));
    }
    
    public function testAddMember_FullValidArray()
    {
        // check if add member works, with a full and valid Array
        $this->addEntry();
    }
    
    public function testAddMember_MinimalValidArray()
    {
        // check if add member works, if a minimal valid array was given
        // minimal means: at least name is given and the automatic first/last date was saved
        $array = array(
            "firstname"     => "Max",
            "lastname"      => "",
            "email"         => "",
            "building"      => "",
            "room"          => "",
            "phone"         => "",
            "fax"           => "",
            "location"      => "",
            "department"    => 0,
            "intern"        => 0
        );
        $this->assertTrue($this->memberCommandHandler->addMember($array));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $result = $this->db->pselect1();
        unset($result["id"]);
        unset($result["lw_first_date"]);
        unset($result["lw_last_date"]);
        
        $this->assertEquals($array,$result);
    }
    
    public function testSaveMember_FullValidArray()
    {
        // check if save member works, with a full and valid Array
        $this->addEntry();
        
        $array2 = array(
            "firstname"     => "Maximilia",
            "lastname"      => "Musterfrau",
            "email"         => "m.musterfrau@logic-works.de",
            "building"      => "Ranzel01",
            "room"          => "02",
            "phone"         => "022133665544",
            "fax"           => "022133665544-22",
            "location"      => "Germany",
            "department"    => 1,
            "intern"        => 1
        );
        
        $this->assertTrue($this->memberCommandHandler->saveMember(1, $array2));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $result2 = $this->db->pselect1();
        unset($result2["id"]);
        unset($result2["lw_first_date"]);
        unset($result2["lw_last_date"]);
        $this->assertEquals($array2,$result2);
    }
    
    public function testSaveMember_MinimalValidArray()
    {
        // check if save member works, if a minimal valid array was given
        // minimal means: at least the id is given and the automatic first/last date was saved
        $this->addEntry();
        
        $array2 = array(
            "firstname"     => "",
            "lastname"      => "",
            "email"         => "",
            "building"      => "",
            "room"          => "",
            "phone"         => "",
            "fax"           => "",
            "location"      => "",
            "department"    => 0,
            "intern"        => 0
        );
        
        $this->assertTrue($this->memberCommandHandler->saveMember(1, $array2));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $result2 = $this->db->pselect1();
        unset($result2["id"]);
        unset($result2["lw_first_date"]);
        unset($result2["lw_last_date"]);
        $this->assertEquals($array2,$result2);
    }
    
    public function testDeleteMember_WithExistingId()
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with the given $id (which should be 1)
        // check if delete works (member with id is deleted
        
        $this->addEntry();
        
        $this->assertTrue($this->memberCommandHandler->deleteMember(1));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $this->assertEmpty($this->db->pselect1());
    }
    
    public function testDeleteMember_WithNotExistingId()
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with an $id other than 1
        // check if delete throws an exception, telling the given ID doesn't exist
        $this->addEntry();
        
        $this->assertTrue($this->memberCommandHandler->deleteMember(999));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 999 ");
        $this->assertEmpty($this->db->pselect1());
    }
    
    public function addEntry()
    {
        $array = array(
            "firstname"     => "Max",
            "lastname"      => "Mustermann",
            "email"         => "m.mustermann@logic-works.de",
            "building"      => "Ranzel02",
            "room"          => "01",
            "phone"         => "02211553366",
            "fax"           => "02211553366-55",
            "location"      => "Germany",
            "department"    => 1,
            "intern"        => 0
        );
        $this->assertTrue($this->memberCommandHandler->addMember($array));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $result = $this->db->pselect1();
        unset($result["id"]);
        unset($result["lw_first_date"]);
        unset($result["lw_last_date"]);
        
        $this->assertEquals($array,$result);
    }
}
