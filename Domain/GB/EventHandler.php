<?php

namespace lwMembersearch\Domain\GB;

class EventHandler 
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
    }
    
    public function getInstance()
    {
        return new EventHandler();
    }

    public function execute($event)
    {
        $this->event = $event;
        $method = $this->event->getEventName();
        return $this->$method();
    }

    protected function returnRenderedView($view)
    {
        $this->event->getResponse()->setOutputByKey('output', $view->render());
        return $this->event->getResponse();
    }
    
    protected function getListView()
    {
        return $this->returnRenderedView(new \lwMembersearch\Domain\GB\View\GbList());
    }
    
    protected function deleteById()
    {
        try {
            $this->dic->getGbRepository()->deleteObjectById($this->event->getParameterByKey('id'));
            $this->event->getResponse()->setParameterByKey('cmd', 'showGbList');
            $this->event->getResponse()->setParameterByKey('response', 2);
            return $this->event->getResponse();
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }        
    }    
    
    public function getEditFormView()
    {
        if ($this->event->getParameterByKey('error')) {
            $dataValueObject = new \LWddd\ValueObject($this->event->getDataByKey('postArray'));
            $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
            $entity->setId($this->event->getParameterByKey('id'));
        }
        else {
            $entity = $this->dic->getGbRepository()->getObjectById($this->event->getParameterByKey('id'));
        }
        $formView = new \lwMembersearch\Domain\GB\View\Form('edit', $entity);
        $formView->setErrors($this->event->getParameterByKey('error'));
        return $this->returnRenderedView($formView);
    }    
    
    public function save()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($this->event->getDataByKey('postArray'));
            $result = $this->dic->getGbRepository()->saveObject($this->event->getParameterByKey('id'), $dataValueObject);
            $this->event->getResponse()->setParameterByKey('cmd', 'showGbList');
            $this->event->getResponse()->setParameterByKey('response', 1);
            return $this->event->getResponse();
        }
        catch (\LWddd\validationErrorsException $e) {
            $this->event->setParameterByKey('error', $e->getErrors());
            return $this->getEditFormView();
        }        
    }    
    
    public function getAddFormView()
    {
        $dataValueObject = new \LWddd\ValueObject($this->event->getDataByKey('postArray'));
        $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        $formView = new \lwMembersearch\Domain\GB\View\Form('add', $entity);
        $formView->setErrors($this->event->getParameterByKey('error'));
        return $this->returnRenderedView($formView);
    }
    
    public function add()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($this->event->getDataByKey('postArray'));
            $result = $this->dic->getGbRepository()->saveObject(false, $dataValueObject);
            $this->event->getResponse()->setParameterByKey('cmd', 'showGbList');
            $this->event->getResponse()->setParameterByKey('response', 1);
            return  $this->event->getResponse();
        }
        catch (\LWddd\validationErrorsException $e) {
            $this->event->setParameterByKey('error', $e->getErrors());
            return $this->getAddFormView();
        }
    }    
}