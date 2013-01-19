<?php

namespace lwMembersearch\Domain\GB\Specification;

class isDeletable 
{
    public function __construct()
    {
    }
    
    static public function getInstance()
    {
        return new isDeletable();
    }
    
    public function isSatisfiedBy(\lwMembersearch\Domain\GB\Object\gb $gb)
    {
        return true;
    }
}