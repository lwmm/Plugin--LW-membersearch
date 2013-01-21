<?php

namespace lwMembersearch\Domain\FB\Model;

class Repository extends \LWddd\Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->baseNamespace = "lwMembersearch\\Domain\\FB\\";
    }
    
    protected function buildObjectById($id)
    {
        return new \lwMembersearch\Domain\FB\Object\fb($id);
    }    
    
    public function getAllObjectsByCategoryAggregate($categoryId)
    {
        $items = $this->getQueryHandler()->loadAllEntriesByCategoryId($categoryId);
        return $this->buildAggregateFromQueryResult($items);
    }
}