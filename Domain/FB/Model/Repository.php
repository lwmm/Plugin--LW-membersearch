<?php

namespace lwMembersearch\Domain\FB\Model;

class Repository extends \LWddd\Repository
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $this->commandHandler = new CommandHandler($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $this->queryHandler = new QueryHandler($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
    
    protected function getIsValidSpecification()
    {
        if (!$this->isValidSpecification) {
            $this->isValidSpecification = \lwMembersearch\Domain\FB\Specification\isValid::getInstance();
        }
        return $this->isValidSpecification;
    }
    
    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = \lwMembersearch\Domain\FB\Model\Factory::getInstance();
        }
        return $this->factory;
    }
    
    protected function getIsDeletableSpecification()
    {
        if (!$this->isDeletableSpecification) {
            $this->isDeletableSpecification = \lwMembersearch\Domain\FB\Specification\isDeletable::getInstance();
        }
        return $this->isDeletableSpecification;
    }
    
    protected function getDataValueObjectFilter()
    {
        if (!$this->DataValueObjectFilter) {
            $this->DataValueObjectFilter = $this->dic->getFbFilter();
        }
        return $this->DataValueObjectFilter;
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