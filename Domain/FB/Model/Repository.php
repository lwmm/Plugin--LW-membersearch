<?php

namespace lwMembersearch\Domain\FB\Model;

class Repository extends \LWddd\Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->commandHandler = new CommandHandler($this->dic->getDbObject());
        $this->queryHandler = new QueryHandler($this->dic->getDbObject());
        $this->isValidSpecification = \lwMembersearch\Domain\FB\Specification\isValid::getInstance();
        $this->factory = \lwMembersearch\Domain\FB\Model\Factory::getInstance();
        $this->isDeletableSpecification = \lwMembersearch\Domain\FB\Specification\isDeletable::getInstance();
        $this->DataValueObjectFilter = $this->dic->getFbFilter();
        $this->ObjectClassName = '\lwMembersearch\Domain\FB\Object\fb';
    }
    
    public function getAllObjectsByCategoryAggregate($categoryId)
    {
        $items = $this->getQueryHandler()->loadAllEntriesByCategoryId($categoryId);
        return $this->buildAggregateFromQueryResult($items);
    }
}