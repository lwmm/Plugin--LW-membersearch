<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

class memberCommandHandlerTest extends \PHPUnit_Framework_TestCase {

    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        //CREATE TABLE
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //DROP TABLE
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
    }
    
    public function testAddMember_EmptyArray($array)
    {
        // check if add member throws Exception, if an empty Array was given
    }
    
    public function testAddMember_FullValidArray($array)
    {
        // check if add member works, with a full and valid Array
    }
    
    public function testAddMember_InValidArray($array)
    {
        // check if add member throws Exception, if an invalid array was given
    }
    
    public function testAddMember_MinimalValidArray($array)
    {
        // check if add member works, if a minimal valid array was given
        // minimal means: at least name is given and the automatic first/last date was saved
    }
    
    public function testSaveMember_EmptyArray($id, $array)
    {
        // check if save member throws Exception, if an empty Array was given
    }
    
    public function testSaveMember_FullValidArray($id, $array)
    {
        // check if save member works, with a full and valid Array
    }
    
    public function testSaveMember_InValidArray($id, $array)
    {
        // check if save member throws Exception, if an invalid array was given
    }
    
    public function testSaveMember_MinimalValidArray($id, $array)
    {
        // check if save member works, if a minimal valid array was given
        // minimal means: at least the id is given and the automatic first/last date was saved
    }
    
    public function testDeleteMember_WithExistingId($id)
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with the given $id (which should be 1)
        // check if delete works (member with id is deleted
    }
    
    public function testDeleteMember_WithNotExistingId($id)
    {
        // add a new member and put ID in variable (id must be 1)
        // delete member with an $id other than 1
        // check if delete throws an exception, telling the given ID doesn't exist
    }
    
    public function testDeleteMember_WithIdIsFalse($id)
    {
        // check if delete throws an exception, telling that no ID was given
    }

    public function testDeleteMember_WithIdIsNotNumeric($id)
    {
        // check if delete throws an exception, telling that the given ID is not numeric
    }
}