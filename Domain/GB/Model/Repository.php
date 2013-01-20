<?php

namespace lwMembersearch\Domain\GB\Model;

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
            $this->isValidSpecification = \lwMembersearch\Domain\GB\Specification\isValid::getInstance();
        }
        return $this->isValidSpecification;
    }
    
    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = \lwMembersearch\Domain\GB\Model\Factory::getInstance();
        }
        return $this->factory;
    }
    
    protected function getIsDeletableSpecification()
    {
        if (!$this->isDeletableSpecification) {
            $this->isDeletableSpecification = \lwMembersearch\Domain\GB\Specification\isDeletable::getInstance();
        }
        return $this->isDeletableSpecification;
    }
    
    protected function getDataValueObjectFilter()
    {
        if (!$this->DataValueObjectFilter) {
            $this->DataValueObjectFilter = $this->dic->getGbFilter();
        }
        return $this->DataValueObjectFilter;
    }
    
    protected function buildObjectById($id)
    {
        return new \lwMembersearch\Domain\GB\Object\gb($id);
    }
}