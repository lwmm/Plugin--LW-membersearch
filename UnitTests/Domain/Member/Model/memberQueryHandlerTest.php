<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once(dirname(__FILE__) . '/../../../../Domain/Member/Model/memberQueryHandler.php');
require_once(dirname(__FILE__) . '/../../../../Domain/Member/Model/memberCommandHandler.php');
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_object.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_db_mysqli.class.php';
require_once dirname(__FILE__) . '/../../../../../../../../c_libraries/lw/lw_registry.class.php';
require_once dirname(__FILE__) . '/../../../Config/phpUnitConfig.php';

class memberQueryHandlerTest extends \PHPUnit_Framework_TestCase {

    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        //INSERT Demo Data (at least 12+ members)
        //several members with the same location
        //several members with the same first name
        //several members with the same department
        //mixe in some that are intern for each parameter
        $phpUnitConfig = new phpUnitConfig();
        $config = $phpUnitConfig->getConfig();
        
        $db = new lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["db"]);
        $db->connect();
        $this->db = $db;
        
        $this->memberQueryHandler   = new \lwMembersearch\Domain\Member\Model\memberQueryHandler($this->db);
        $this->assertTrue($this->createTable());
        $this->fillTable();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //TRUNCATE Table
        $this->db->setStatement("DROP TABLE t:lw_membersearch ");
        $this->db->pdbquery();
    }

    public function testloadMemberById_WithExistingId()
    {
        // intern = (false) and id = (from a member that is not intern)
        // check if correct member was returned
        $result = $this->memberQueryHandler->loadMemberById(1);
        unset($result["id"]);
        unset($result["lw_first_date"]);
        unset($result["lw_last_date"]);
        $this->assertEquals($result["intern"], 0);
        $this->assertEquals(array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ), $result);
        // intern = (false) and id = (from a member that is intern)
        // check if an empty array was returned
        $result = $this->memberQueryHandler->loadMemberById(2);
        $this->assertEmpty($result);
        
        // intern = (true) and id = (from a member that is intern)
        // check if correct member was returned
        $result = $this->memberQueryHandler->loadMemberById(2,true);
        unset($result["id"]);
        unset($result["lw_first_date"]);
        unset($result["lw_last_date"]);
        $this->assertEquals(array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ), $result);
    }
    
    public function testloadMemberById_WithNotExistingId()
    {
        // intern = (false)        
        // check if empty array was returned
       
       $result =  $this->memberQueryHandler->loadMemberById(999);
       $this->assertEmpty($result);
    }
    
    public function testLoadAllMembers_WithDemoContent()
    {
        // intern = (false)
        // check if all members from the demo content are present that are not intern
        $result = $this->memberQueryHandler->loadAllMembers();
        foreach($result as $entry){
            unset($entry["id"]);
            unset($entry["lw_first_date"]);
            unset($entry["lw_last_date"]);
            $array[] = $entry;
        }
        $this->assertEquals($array, array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )));
        // intern = (true)
        // check if all members from the demo content are present
        $result2 = $this->memberQueryHandler->loadAllMembers(true);
        foreach($result2 as $entry2){
            unset($entry2["id"]);
            unset($entry2["lw_first_date"]);
            unset($entry2["lw_last_date"]);
            $array2[] = $entry2;
        }
        $this->assertEquals($array2, array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )));
    }
    
    public function testLoadAllMembers_WithoutDemoContent()
    {
        // truncate table
        // intern = (false)
        // check if an empty array is returned
        $this->tearDown();
        $this->assertTrue($this->createTable());
        $this->assertEmpty($this->memberQueryHandler->loadAllMembers());
    }
    
    public function testLoadMembersByDepartment_WithExistingDepartment()
    {
        // intern = (false)
        // check if an array with all members and only members from the given Department, that are not intern, is returned
        $array = array();
        $result = $this->memberQueryHandler->loadMembersByDepartment(1);  
        foreach($result as $value){
            unset($value["id"]);
            unset($value["lw_first_date"]);
            unset($value["lw_last_date"]);
           $array[] = $value;
        }
        
        $assertedArray = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        
        $this->assertEquals($array, $assertedArray);
        
        // intern = (true)
        // check if an array with all members and only members from the given Department is returned
        
        $array2 = array();
        
        $result2 = $this->memberQueryHandler->loadMembersByDepartment(1, true);  
        foreach($result2 as $value2){
            unset($value2["id"]);
            unset($value2["lw_first_date"]);
            unset($value2["lw_last_date"]);
           $array2[] = $value2;
        }
        
        $assertedArray2 = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        
        $this->assertEquals($assertedArray2, $array2);
    }
    
    public function testLoadMembersByDepartment_WithNotExistingDepartment()
    {
        // check if an empty array is returned
        $result = $this->memberQueryHandler->loadMembersByDepartment("gibtsnicht");
        $this->assertEmpty($result);
    }
    
    public function testLoadMembersByName_WithExistingName()
    {
        $array = array(
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas111",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany111",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas222",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany222",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas333",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany333",
                "department"    => 1,
                "intern"        => 1
                )
        );
        foreach($array as $value){
            $this->assertTrue($this->addMember($value));
        }
        // intern = (false)
        // check if an array with all members and only members with the given first name, that are not intern, is returned
        $result = $this->memberQueryHandler->loadMembersByName("Timo");
        $arr = array();
        foreach($result as $value){
          unset($value["id"]);  
          unset($value["lw_first_date"]);  
          unset($value["lw_last_date"]);  
          $arr[] = $value;
        };
        
        $assertedArray = array(
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas111",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany111",
                "department"    => 1,
                "intern"        => 0
                )
        );
        
        $this->assertEquals($arr,$assertedArray);
        // intern = (true)
        // check if an array with all members and only members with the given first name is returned
        
        $result2 = $this->memberQueryHandler->loadMembersByName("Timo", true);
        $arr2 = array();
        foreach($result2 as $value2){
          unset($value2["id"]);  
          unset($value2["lw_first_date"]);  
          unset($value2["lw_last_date"]);  
          $arr2[] = $value2;
        };
        
        $this->assertEquals($arr2,$array);
    }

    public function testLoadMembersByName_WithNotExistingName()
    {
        // check if an empty array is returned
        $result = $this->memberQueryHandler->loadMembersByName("Teemo");
        $this->assertEmpty($result);
    }

    public function testLoadMembersByLocation_WithExistingLocation()
    {
        // intern = (false)
        // check if an array with all members and only members from the given location, that are not intern, is returned
        $arr = array();
        $result = $this->memberQueryHandler->loadMembersByLocation("Germany");
        foreach ($result as $value) {
            unset($value["id"]);
            unset($value["lw_first_date"]);
            unset($value["lw_last_date"]);
            $arr[] = $value;
        }
        $assertedArray = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
           array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        $this->assertEquals($arr, $assertedArray);
        
        // intern = (true)
        // check if an array with all members and only members from the given location is returned
        $arr2 = array();
        $result2 = $this->memberQueryHandler->loadMembersByLocation("Germany",true);
        foreach ($result2 as $value2) {
            unset($value2["id"]);
            unset($value2["lw_first_date"]);
            unset($value2["lw_last_date"]);
            $arr2[] = $value2;
        }
        $assertedArray2 = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        $this->assertEquals($arr2, $assertedArray2);
    }

    public function testLoadMembersByLocation_WithNotExistingLocation()
    {
        $result = $this->memberQueryHandler->loadMembersByLocation("Romolus");
        $this->assertEmpty($result);
        
    }

    public function testLoadMembersByFilter_WithEmptyFilter()
    {
        // intern = (false)
        // check if all members from the demo content, that are not intern, are present
        $arr = array();
        $result = $this->memberQueryHandler->loadMembersByFilter(array());
        foreach ($result as $value) {
            unset($value["id"]);
            unset($value["lw_first_date"]);
            unset($value["lw_last_date"]);
            $arr[] = $value;
        }
        
        $assertedArray = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        
        $this->assertEquals($arr, $assertedArray);
        // intern = (true)
        // check if all members from the demo content are present
        $arr2 = array();
        $result2 = $this->memberQueryHandler->loadMembersByFilter(array(),true);
        foreach ($result2 as $value2) {
            unset($value2["id"]);
            unset($value2["lw_first_date"]);
            unset($value2["lw_last_date"]);
            $arr2[] = $value2;
        }
        
        $assertedArray2 = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        
        $this->assertEquals($arr2, $assertedArray2);
    }
    
    public function testLoadMembersByFilter_WithCompleteValidFilter()
    {
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // choose filter values, that will return at least 2 members (intern and not intern) form the demo content
        
        // intern = (false)
        // check if all members from the demo content for that filter and that are not intern are present
        $arr = array();
        $result = $this->memberQueryHandler->loadMembersByFilter(array("location" => "Germany", "department" => 1));
        foreach($result as $value){
            unset($value["id"]);
            unset($value["lw_first_date"]);
            unset($value["lw_last_date"]);
            $arr[] = $value;
        }
        $assertedArray = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        $this->assertEquals($arr, $assertedArray);
        
        // intern = (true)
        // check if all members from the demo content for that filter are present
        
        $arr2 = array();
        $result2 = $this->memberQueryHandler->loadMembersByFilter(array("location" => "Germany", "department" => 1), true);
        foreach($result2 as $value2){
            unset($value2["id"]);
            unset($value2["lw_first_date"]);
            unset($value2["lw_last_date"]);
            $arr2[] = $value2;
        }
        $assertedArray2 = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );
        $this->assertEquals($arr2, $assertedArray2);
    }
        
    public function fillTable()
    {
        $array = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Arktis",
                "department"    => 2,
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "building"      => "Ranzel02",
                "room"          => "01",
                "phone"         => "02211553366",
                "fax"           => "02211553366-55",
                "location"      => "Germany",
                "department"    => 1,
                "intern"        => 0
                )
        );

        foreach($array as $value){
            $this->assertTrue($this->addMember($value));
        }
    }
    
    public function addMember($value)
    {
        $this->db->setStatement("INSERT INTO t:lw_membersearch ( firstname, lastname, email, building, room, phone, fax, location, department, intern, lw_first_date, lw_last_date ) VALUES ( :firstname, :lastname, :email, :building, :room, :phone, :fax, :location, :department, :intern, :lw_first_date, :lw_last_date ) ");
        $this->db->bindParameter("firstname", "s", $value["firstname"]);
        $this->db->bindParameter("lastname", "s", $value["lastname"]);
        $this->db->bindParameter("email", "s", $value["email"]);
        $this->db->bindParameter("building", "s", $value["building"]);
        $this->db->bindParameter("room", "s", $value["room"]);
        $this->db->bindParameter("phone", "s", $value["phone"]);
        $this->db->bindParameter("fax", "s", $value["fax"]);
        $this->db->bindParameter("location", "s", $value["location"]);
        $this->db->bindParameter("intern", "i", $value["intern"]);
        $this->db->bindParameter("department", "i", $value["department"]);
        $this->db->bindParameter("lw_first_date", "i", date("YmdHis"));
        $this->db->bindParameter("lw_last_date", "i", date("YmdHis"));

        return $this->db->pdbquery();
    }
    
    public function createTable()
    {
        $this->db->setStatement("CREATE TABLE IF NOT EXISTS lw_membersearch (
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
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
        
        return $this->db->pdbquery();
    }
}
