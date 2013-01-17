<?php

namespace lwMembersearch\Domain\Member\Model;

class memberQueryHandler
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function loadMemberById($id, $intern=false)
    {
    }
    
    public function loadAllMembers($intern=false)
    {
    }
    
    public function loadMembersByDepartment($department, $intern=false)
    {
    }
    
    public function loadMembersByName($name, $intern=false)
    {
    }

    public function loadMembersByLocation($location, $intern=false)
    {
    }

    public function loadMembersByFilter($filterArray, $intern=false)
    {
    }    
}