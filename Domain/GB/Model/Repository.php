<?php

namespace lwMembersearch\Domain\GB\Model;

class Repository
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
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
    
    protected function buildAggregateFromQueryResult($items)
    {
        foreach($items as $item) {
             $entities[] =  $this->buildObjectByArray($item);
        }
        return new \LWddd\EntityAggregate($entities);
    }
    
    public function getAllObjectsAggregate()
    {
        $items = $this->getQueryHandler()->loadAllEntries();
        return $this->buildAggregateFromQueryResult($items);
    }
    
    public function buildObjectByArray($data)
    {
        $object = new \lwMembersearch\Domain\GB\Object\gb($data['id']);
        $object->setDataValueObject(new \LWddd\ValueObject($data));
        $object->setLoaded();
        $object->unsetDirty();
        return $object;
    }
    
    public function getObjectById($id)
    {
        $data = $this->getQueryHandler()->loadObjectById($id);
        return $this->buildObjectByArray($data);
    }
    
    protected function prepareObjectToSave($id, $dataObject) 
    {
        $DataValueObjectFiltered = $this->dic->getGbFilter()->filter($dataObject);
        if (!$id) {
            $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($DataValueObjectFiltered);
        }
        else {
            $entity = $this->dic->getGbRepository()->getObjectById($id);
            $entity->setDataValueObject($DataValueObjectFiltered);
        }
        return $entity;
    }
    
    public function saveObject($id, $dataObject)
    {
        $entity = $this->prepareObjectToSave($id, $dataObject);
        $isValidSpecification = \lwMembersearch\Domain\GB\Specification\isValid::getInstance();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            if ($entity->getId() > 0 ) {
                $result = $this->getCommandHandler()->saveEntity($entity->getId(), $entity->getValues());
                $id = $entity->getId();
            }
            else {
                $result = $this->getCommandHandler()->addEntity($entity->getValues());
                $id = $result;
            }

            if ($result) {
                $entity->setLoaded();
                $entity->unsetDirty();
            }
            else {
                if ($id > 0 ) {
                    $entity->setLoaded();
                }
                else {
                    $entity->unsetLoaded();
                }
                $entity->setDirty();
                throw new Exception('An DB Error occured saving the Entity');
            }
            return $id;
        }
        else {
            die("e");
            $exception = new \Fab\Domain\Event\Specification\validationErrorsException('Error');
            $exception->setErrors($isValidSpecification->getErrors());
            throw $exception;
        }
    }
    
    public function deleteObjectById($id)
    {
        $gb = $this->getObjectById($id);
        if (\lwMembersearch\Domain\GB\Specification\isDeletable::getInstance()->isSatisfiedBy($gb)) {
            return $this->getCommandHandler()->deleteEntityById($id);
        }
        else {
            throw new Exception('Delete not allowed');
        }
    }
}