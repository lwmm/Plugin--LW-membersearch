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
    
    public function testAddMember_EmptyArray()
    {
        // check if add member throws Exception, if an empty Array was given
        try{
            $this->memberCommandHandler->addMember(array());
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"ERROR: addMember got empty Array");
    }
    
    public function testAddMember_FullValidArray()
    {
        // check if add member works, with a full and valid Array
        $this->addEntry();
    }
    
    public function testAddMember_InValidArray($array)
    {
        // check if add member throws Exception, if an invalid array was given
        for($i = 0; $i >= 260; $i++){
            $firstname.= "A";
            $lastname.= "B";
            $location.= "C";
            $department.= "D";
        }
        $array = array(
            "firstname"     => $firstname,
            "lastname"      => $lastname,
            "email"         => "mmustermannlogicworksde",
            "location"      => $location,
            "department"    => $department,
            "intern"        => "s"
        );
        try{
            $this->memberCommandHandler->addMember($array);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Member-Array is invalid!");
    }
    
    public function testAddMember_MinimalValidArray($array)
    {
        // check if add member works, if a minimal valid array was given
        // minimal means: at least name is given and the automatic first/last date was saved
        $array = array(
            "firstname"     => "Max",
            "lastname"      => "",
            "email"         => "",
            "location"      => "",
            "department"    => "",
            "intern"        => ""
        );
        $this->assertTrue($this->memberCommandHandler->addMember($array));
        $this->db->setStatement("SELECT * FROM t:lw_membersearch WHERE id = 1 ");
        $result = $this->db->pselect1();
        unset($result["id"]);
        unset($result["lw_first_date"]);
        unset($result["lw_last_date"]);
        
        $this->assertEquals($array,$result);
    }
    
    public function testSaveMember_EmptyArray($id, $array)
    {
        // check if save member throws Exception, if an empty Array was given
        $this->addEntry();

        try{
            $this->memberCommandHandler->saveMember(1, array());
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Member-Array is empty!");
    }
    
    public function testSaveMember_FullValidArray($id, $array)
    {
        // check if save member works, with a full and valid Array
        $this->addEntry();
        
        $array2 = array(
            "firstname"     => "Maximilia",
            "lastname"      => "Musterfrau",
            "email"         => "m.musterfrau@logic-works.de",
            "location"      => "Germany",
            "department"    => "Office",
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
    
    public function testSaveMember_InValidArray($id, $array)
    {
        // check if save member throws Exception, if an invalid array was given
        $this->addEntry();
        
        for($i = 0; $i >= 260; $i++){
            $firstname.= "A";
            $lastname.= "B";
            $location.= "C";
            $department.= "D";
        }
        $array = array(
            "firstname"     => $firstname,
            "lastname"      => $lastname,
            "email"         => "mmustermannlogicworksde",
            "location"      => $location,
            "department"    => $department,
            "intern"        => "s"
        );
        
        try{
            $this->memberCommandHandler->saveMember(1, $array);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Update-Array is invalid!");
    }
    
    public function testSaveMember_MinimalValidArray($id, $array)
    {
        // check if save member works, if a minimal valid array was given
        // minimal means: at least the id is given and the automatic first/last date was saved
        $this->addEntry();
        
        $array2 = array(
            "firstname"     => "",
            "lastname"      => "",
            "email"         => "",
            "location"      => "",
            "department"    => "",
            "intern"        => ""
        );
        
        $this->assertTrue($this->memberCommandHandler->saveMember(1, $array2));
        $result2 = $this->memberQueryHandler->loadMemberById(1);
        unset($result2["id"]);
        unset($result2["lw_first_date"]);
        unset($result2["lw_last_date"]);
        $this->assertEquals($array2,$result2);
    }
    
    public function testDeleteMember_WithExistingId($id)
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with the given $id (which should be 1)
        // check if delete works (member with id is deleted
        
        $this->addEntry();
        
        $this->assertTrue($this->memberCommandHandler->deleteMember(1));
        $this->assertEmpty($this->memberCommandHandler->loadMemberById(1));
    }
    
    public function testDeleteMember_WithNotExistingId($id)
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with an $id other than 1
        // check if delete throws an exception, telling the given ID doesn't exist
        $this->addEntry();
        
        try{
            $this->memberCommandHandler->deleteMember(5);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Id is not existing!");
    }
    
    public function testDeleteMember_WithIdIsFalse($id) #keine id uebergeben
    {
        // check if delete throws an exception, telling that no ID was given
        $this->addEntry();
        
        try{
            $this->memberCommandHandler->deleteMember(false);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Id is missing!");
    }

    public function testDeleteMember_WithIdIsNotNumeric($id)
    {
        // check if delete throws an exception, telling that the given ID is not numeric
        $this->addEntry();
        
        try{
            $this->memberCommandHandler->deleteMember("a");
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Id is not numeric!");
    }
    
    public function addEntry()
    {
        $array = array(
            "firstname"     => "Max",
            "lastname"      => "Mustermann",
            "email"         => "m.mustermann@logic-works.de",
            "location"      => "Germany",
            "department"    => "Office",
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