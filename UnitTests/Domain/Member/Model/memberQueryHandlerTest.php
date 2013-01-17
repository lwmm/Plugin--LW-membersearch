<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

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
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //TRUNCATE Table
    }

    public function testloadMemberById_WithExistingId()
    {
        // intern = (false) and id = (from a member that is not intern)
        // check if correct member was returned
        
        // intern = (false) and id = (from a member that is intern)
        // check if an empty array was returned
        
        // intern = (true) and id = (from a member that is intern)
        // check if correct member was returned
    }
    
    public function testloadMemberById_WithNotExistingId()
    {
        // intern = (false)        
        // check if Exception is thrown
    }
    
    public function testloadMemberById_WithIdIsFalse()
    {
        // intern = (false)
        // check if method throws an exception, telling the given ID doesn't exist
    }

    public function testloadMemberById_WithIdIsNotNumeric()
    {
        // intern = (false)        
        // check if method throws an exception, telling the given ID isn't numeric
    }
    
    public function testLoadAllMembers_WithDemoContent()
    {
        // intern = (false)
        // check if all members from the demo content are present that are not intern
        
        // intern = (true)
        // check if all members from the demo content are present
    }
    
    public function testLoadAllMembers_WithoutDemoContent()
    {
        // truncate table
        // intern = (false)
        // check if an empty array is returned
    }
    
    public function testLoadMembersByDepartment_WithExistingDepartment()
    {
        // intern = (false)
        // check if an array with all members and only members from the given Department, that are not intern, is returned
        
        // intern = (true)
        // check if an array with all members and only members from the given Department is returned
    }
    
    public function testLoadMembersByDepartment_WithNotExistingDepartment()
    {
        // check if an empty array is returned
    }
    
    public function testLoadMembersByName_WithExistingName()
    {
        // intern = (false)
        // check if an array with all members and only members with the given first name, that are not intern, is returned
        
        // intern = (true)
        // check if an array with all members and only members with the given first name is returned
    }

    public function testLoadMembersByName_WithNotExistingName()
    {
        // check if an empty array is returned
    }

    public function testLoadMembersByLocation_WithExistingLocation()
    {
        // intern = (false)
        // check if an array with all members and only members from the given location, that are not intern, is returned
        
        // intern = (true)
        // check if an array with all members and only members from the given location is returned
    }

    public function testLoadMembersByLocation_WithNotExistingLocation()
    {
        // check if an empty array is returned
    }

    public function testLoadMembersByFilter_WithEmptyFilter()
    {
        // intern = (false)
        // check if all members from the demo content, that are not intern, are present
        
        // intern = (true)
        // check if all members from the demo content are present
    }
    
    public function testLoadMembersByFilter_WithCompleteValidFilter()
    {
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // choose filter values, that will return at least 2 members (intern and not intern) form the demo content
        
        // intern = (false)
        // check if all members from the demo content for that filter and that are not intern are present
        
        // intern = (true)
        // check if all members from the demo content for that filter are present
    }
    
    public function testLoadMembersByFilter_WithInValidFilter()
    {
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only lastname is invalid
        // check if an Exception is thrown
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only firstname is invalid
        // check if an Exception is thrown
        
        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only email is invalid
        // check if an Exception is thrown

        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only location is invalid
        // check if an Exception is thrown

        // $filterArray = array("lastname"=>"", "firstname", "email"=>"", location=>"", department=>"");
        // with only department is invalid
        // check if an Exception is thrown
    }
}