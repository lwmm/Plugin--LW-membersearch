<?php

namespace lwMembersearch\Domain\FB;

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
    
    protected function returnRenderedView($view)
    {
        $this->response->addOutputByName('lwMembersearchOutput', $view->render());
        return $this->response;
    }    
    
    public function addFb($categoryId, $PostArray)
    {
        try {
            $dataValueObject = new \LWddd\ValueObject(array_merge(array("category_id"=>$categoryId),$PostArray));
            $result = $this->dic->getFbRepository()->saveObject(false, $dataValueObject);
            $this->response->setReloadCmd('editGbForm', array('response'=>1, 'id'=>$categoryId));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->getFbAddForm($PostArray, $e->getErrors());
        }        
    }
    
    public function getFbAddForm($PostArray, $errors)
    {
        $dataValueObject = new \LWddd\ValueObject($PostArray);
        $entity = \lwMembersearch\Domain\FB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        $formView = new \lwMembersearch\Domain\FB\View\Form('add', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    public function deleteFbById($categoryId, $id)
    {
        try {
            $ok = $this->dic->getFbRepository()->deleteObjectById($id);
            $this->response->setReloadCmd('editGbForm', array('id'=>$categoryId, 'response'=>2));
            return $this->response;
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }         
    }
    
    public function getFbEditForm($id, $PostArray, $errors)
    {
        if ($errors) {
            $dataValueObject = new \LWddd\ValueObject($PostArray);
            $entity = \lwMembersearch\Domain\FB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
            $entity->setId($id);
        }
        else {
            $entity = $this->dic->getFbRepository()->getObjectById($id);
        }
        $formView = new \lwMembersearch\Domain\FB\View\Form('edit', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);        
    }
    
    public function saveFb($categoryId, $id, $PostArray)
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($PostArray);
            $result = $this->dic->getFbRepository()->saveObject($id, $dataValueObject);
            $this->response->setReloadCmd('editGbForm', array('response'=>1, 'id'=>$categoryId));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->getFbEditForm($id, $PostArray, $e->getErrors());
        }
    }
}