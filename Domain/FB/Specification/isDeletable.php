<?php

namespace lwMembersearch\Domain\FB\Specification;

class isDeletable 
{
    public function __construct()
    {
    }
    
    static public function getInstance()
    {
        return new isDeletable();
    }
    
    public function isSatisfiedBy(\lwMembersearch\Domain\FB\Object\fb $fb)
    {
        return true;
    }
}