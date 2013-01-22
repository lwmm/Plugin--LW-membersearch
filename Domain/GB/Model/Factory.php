<?php

namespace lwMembersearch\Domain\GB\Model;

class Factory
{
    static private $identifier = 0;
    static private $instance = false;
    
    protected function __construct() 
    {
        $this->identifier++;
    }
    
    static public function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new Factory();
        }
        return static::$instance;
    }
    
    public function buildNewObjectFromArray($array)
    {
        return $this->buildNewObjectFromValueObject(new \LWddd\ValueObject($array));
    }
    
    public function buildNewObjectFromValueObject($object)
    {
        $entity[$this->identifier] = new \lwMembersearch\Domain\GB\Object\gb();
        $entity[$this->identifier]->setDataValueObject($object);
        $entity[$this->identifier]->setLoaded();
        $entity[$this->identifier]->setDirty();
        return $entity[$this->identifier];
    }
}