<?php

namespace lwMembersearch\Domain\GB\Model;

class Repository extends \LWddd\Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->commandHandler = new CommandHandler($this->dic->getDbObject());
        $this->queryHandler = new QueryHandler($this->dic->getDbObject());
        $this->isValidSpecification = \lwMembersearch\Domain\GB\Specification\isValid::getInstance();
        $this->factory = \lwMembersearch\Domain\GB\Model\Factory::getInstance();
        $this->isDeletableSpecification = \lwMembersearch\Domain\GB\Specification\isDeletable::getInstance();
        $this->DataValueObjectFilter = $this->dic->getGbFilter();
        $this->ObjectClassName = '\lwMembersearch\Domain\GB\Object\gb';
    }
}