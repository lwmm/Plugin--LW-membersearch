<?php

namespace lwMembersearch\Domain\GB\Model;

class Repository extends \LWddd\Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->baseNamespace = "lwMembersearch\\Domain\\GB\\";
    }
    
    protected function buildObjectById($id)
    {
        return new \lwMembersearch\Domain\GB\Object\gb($id);
    }
}