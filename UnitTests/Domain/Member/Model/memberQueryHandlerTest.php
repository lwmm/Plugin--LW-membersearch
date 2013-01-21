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
        $this->memberCommandHandler = new \lwMembersearch\Domain\Member\Model\memberCommandHandler($this->db);
        $this->assertTrue($this->memberCommandHandler->createMemberTable());
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

    public function testloadMemberById_WithExistingId($id, $intern)
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
                "location"      => "Germany",
                "department"    => "Office",
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ), $result);
    }
    
    public function testloadMemberById_WithNotExistingId($id, $intern)
    {
        // intern = (false)        
        // check if Exception is thrown
        try{
            $this->memberQueryHandler->loadMemberById(999);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"ID is not existing!");
    }
    
    public function testloadMemberById_WithIdIsFalse($id, $intern)
    {
        // intern = (false)
        // check if method throws an exception, telling the given ID doesn't exist
        try{
            $this->memberQueryHandler->loadMemberById(false);
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"ID can't be FALSE!");
    }

    public function testloadMemberById_WithIdIsNotNumeric($id, $intern)
    {
        // intern = (false)        
        // check if method throws an exception, telling the given ID isn't numeric
        try{
            $this->memberQueryHandler->loadMemberById("aa");
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"ID is not numeric!");
    }
    
    public function testLoadAllMembers_WithDemoContent($intern)
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )));
        // intern = (true)
        // check if all members from the demo content are present
        $result = $this->memberQueryHandler->loadAllMembers(true);
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )));
    }
    
    public function testLoadAllMembers_WithoutDemoContent($intern)
    {
        // truncate table
        // intern = (false)
        // check if an empty array is returned
        $this->tearDown();
        $this->assertTrue($this->memberCommandHandler->createMemberTable());
        $this->assertEmpty($this->memberQueryHandler->loadAllMembers());
    }
    
    public function testLoadMembersByDepartment_WithExistingDepartment($department, $intern)
    {
        // intern = (false)
        // check if an array with all members and only members from the given Department, that are not intern, is returned
        $this->fillTable();
        $array = array();
        
        $result = $this->memberQueryHandler->loadMembersByDepartment("Office");  
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        
        $this->assertEquals($array, $assertedArray);
        
        // intern = (true)
        // check if an array with all members and only members from the given Department is returned
        
        $array2 = array();
        
        $result2 = $this->memberQueryHandler->loadMembersByDepartment("Office", true);  
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        
        $this->assertEquals($assertedArray2, $array2);
    }
    
    public function testLoadMembersByDepartment_WithNotExistingDepartment($department, $intern)
    {
        // check if an empty array is returned
        $result = $this->memberQueryHandler->loadMembersByDepartment("gibtsnicht");
        $this->assertEmpty($result);
    }
    
    public function testLoadMembersByName_WithExistingName($name, $intern)
    {
        $array = array(
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas111",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany111",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas222",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany222",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas333",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany333",
                "department"    => "Office",
                "intern"        => 1
                )
        );
        foreach($array as $value){
            $this->assertTrue($this->memberCommandHandler->addMember($value));
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Timo",
                "lastname"      => "Iwas111",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany111",
                "department"    => "Office",
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

    public function testLoadMembersByName_WithNotExistingName($name, $intern)
    {
        // check if an empty array is returned
        $result = $this->memberQueryHandler->loadMembersByName("Teemo");
        $this->assertEmpty($result);
    }

    public function testLoadMembersByLocation_WithExistingLocation($location, $intern)
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
           array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        $this->assertEquals($arr2, $assertedArray2);
    }

    public function testLoadMembersByLocation_WithNotExistingLocation($location, $intern)
    {
        $result = $this->memberQueryHandler->loadMembersByLocation("Romolus");
        $this->assertEmpty($result);
        
    }

    public function testLoadMembersByFilter_WithEmptyFilter($filterArray, $intern)
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        
        $this->assertEqulas($arr, $assertedArray);
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
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        
        $this->assertEqulas($arr2, $assertedArray2);
    }
    
    public function testLoadMembersByFilter_WithCompleteValidFilter($filterArray, $intern)
    {
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // choose filter values, that will return at least 2 members (intern and not intern) form the demo content
        
        // intern = (false)
        // check if all members from the demo content for that filter and that are not intern are present
        $arr = array();
        $result = $this->memberQueryHandler->loadMembersByFilter(array("location" => "Germany", "department" => "Office"));
        foreach($result as $value){
            unset($value["id"]);
            unset($value["lw_fist_date"]);
            unset($value["lw_last_date"]);
            $arr[] = $value;
        }
        $assertedArray = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        $this->assertEquals($arr, $assertedArray);
        
        // intern = (true)
        // check if all members from the demo content for that filter are present
        
        $arr2 = array();
        $result2 = $this->memberQueryHandler->loadMembersByFilter(array("location" => "Germany", "department" => "Office"), true);
        foreach($result2 as $value2){
            unset($value2["id"]);
            unset($value2["lw_fist_date"]);
            unset($value2["lw_last_date"]);
            $arr2[] = $value2;
        }
        $assertedArray2 = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );
        $this->assertEquals($arr2, $assertedArray2);
    }
    
    public function testLoadMembersByFilter_WithInValidFilter($filterArray, $intern)
    {
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only lastname is invalid
        // check if an Exception is thrown
        for($i = 0; $i >= 260; $i++){
            $lastname.= "A";
        }
        try{
            $this->memberQueryHandler->loadMembersByFilter(array("lastname" => $lastname, "firstname" => "Otto", "email" => "k.heinz@autohaus.de", "location" => "Germany", "department" => "Office"));
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Filterarray 'lastname' is invalid!");
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only firstname is invalid
        // check if an Exception is thrown
        
        for($i = 0; $i >= 260; $i++){
            $firstname.= "B";
        }
        try{
            $this->memberQueryHandler->loadMembersByFilter(array("lastname" => "Heinz", "firstname" => $firstname, "email" => "k.heinz@autohaus.de", "location" => "Germany", "department" => "Office"));
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Filterarray 'firstname' is invalid!");
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only email is invalid
        // check if an Exception is thrown

        try{
            $this->memberQueryHandler->loadMembersByFilter(array("lastname" => "Heinz", "firstname" => "Karl", "email" => "kheinzautohausde", "location" => "Germany", "department" => "Office"));
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Filterarray 'email' is invalid!");
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only location is invalid
        // check if an Exception is thrown

        for($i = 0; $i >= 26; $i++){
            $location.= "L";
        }
        try{
            $this->memberQueryHandler->loadMembersByFilter(array("lastname" => "Heinz", "firstname" => "Karl", "email" => "k.heinz@autohaus.de", "location" => $location, "department" => "Office"));
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Filterarray 'location' is invalid!");
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only department is invalid
        // check if an Exception is thrown
        
        for($i = 0; $i >= 26; $i++){
            $department.= "D";
        }
        try{
            $this->memberQueryHandler->loadMembersByFilter(array("lastname" => "Heinz", "firstname" => "Karl", "email" => "k.heinz@autohaus.de", "location" => "Germany", "department" => $department));
        } catch (Exception $e){
            $thrownException = true;
        }
        $this->assertTrue($thrownException);
        $this->assertEquals($e->getMessage(),"Filterarray 'department' is invalid!");
    }
    
    public function fillTable()
    {
        $array = array(
            array(
                "firstname"     => "Max1",
                "lastname"      => "Mustermann1",
                "email"         => "m.mustermann@logic-works.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl1",
                "lastname"      => "Heinz1",
                "email"         => "k.heinz@autohaus.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf1",
                "lastname"      => "Rednose1",
                "email"         => "r.deer@northpol.de1",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto1",
                "lastname"      => "Iwas1",
                "email"         => "otto.eleven@film.de1",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max2",
                "lastname"      => "Mustermann2",
                "email"         => "m.mustermann@logic-works.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl2",
                "lastname"      => "Heinz2",
                "email"         => "k.heinz@autohaus.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf2",
                "lastname"      => "Rednose2",
                "email"         => "r.deer@northpol.de2",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto2",
                "lastname"      => "Iwas2",
                "email"         => "otto.eleven@film.de2",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Max3",
                "lastname"      => "Mustermann3",
                "email"         => "m.mustermann@logic-works.de3",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                ),
            array(
                "firstname"     => "Karl3",
                "lastname"      => "Heinz",
                "email"         => "k.heinz@autohaus.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Rudolf3",
                "lastname"      => "Rednose3",
                "email"         => "r.deer@northpol.de3",
                "location"      => "Arktis",
                "department"    => "ababab",
                "intern"        => 1
                ),
            array(
                "firstname"     => "Otto3",
                "lastname"      => "Iwas",
                "email"         => "otto.eleven@film.de",
                "location"      => "Germany",
                "department"    => "Office",
                "intern"        => 0
                )
        );

        foreach($array as $value){
            $this->assertTrue($this->memberCommandHandler->addMember($value));
        }
    }
}