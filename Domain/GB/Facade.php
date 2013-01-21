<?php

namespace lwMembersearch\Domain\GB;

class Facade 
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
    }
    
    public function getInstance()
    {
        return new Facade();
    }
    
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }
    
    public function addGB($PostArray)
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($PostArray);
            $result = $this->dic->getGbRepository()->saveObject(false, $dataValueObject);
            $this->response->setReloadCmd('showGbList', array('response'=>1));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->getGbAddForm($PostArray, $e->getErrors());
        }
    }
    
    public function getGbAddForm($PostArray, $errors)
    {
        $dataValueObject = new \LWddd\ValueObject($PostArray);
        $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        $formView = new \lwMembersearch\Domain\GB\View\Form('add', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    protected function returnRenderedView($view)
    {
        $this->response->addOutputByName('lwMembersearchOutput', $view->render());
        return $this->response;
    }
    
    public function deleteGbById($id)
    {
        try {
            $ok = $this->dic->getGbRepository()->deleteObjectById($id);
            $this->response->setReloadCmd('showGbList', array('response'=>2));
            return $this->response;
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }        
    }
    
    public function getGbEditForm($id, $PostArray, $errors)
    {
        if ($errors) {
           $dataValueObject = new \LWddd\ValueObject($PostArray);
            $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        }
        else {
            $entity = $this->dic->getGbRepository()->getObjectById($id);
        }
        $formView = new \lwMembersearch\Domain\GB\View\Form('edit', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    public function saveGB($id, $PostArray)
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($PostArray);
            $result = $this->dic->getGbRepository()->saveObject($id, $dataValueObject);
            $this->response->setReloadCmd('showGbList', array('response'=>1));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->getGbEditForm($id, $PostArray, $e->getErrors());
        }        
    }
    
    public function showGbList()
    {
        return $this->returnRenderedView(new \lwMembersearch\Domain\GB\View\GbList());
    }
}