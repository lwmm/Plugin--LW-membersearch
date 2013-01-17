<?php

namespace lwMembersearch\Domain\Member\Model;

class memberCommandHandler
{
    public function __construct($db, $tableName)
    {
        $this->db = $db;
        $this->tableName = $tableName;
    }
    
    public function createMemberTable()
    {
    }
    
    public function addMember($array)
    {
    }
    
    public function saveMember($id, $array)
    {
    }
    
    public function deleteMember($id)
    {
    }
}