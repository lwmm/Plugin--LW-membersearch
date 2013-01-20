<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2013 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

namespace lwMembersearch\Controller;

class BackendController
{
    public function __construct($response)
    {
        $this->defaultAction = "showListAction";
        $this->dic = new \lwMembersearch\Services\dic();
        $this->request = $this->dic->getLwRequest();
        $this->response = $response;
    }
    
    public function execute()
    {
        $methodName = $this->request->getAlnum('cmd')."Action";
        if (method_exists($this, $methodName)) {
            return call_user_method($methodName, $this);
        }
        else {
            return $this->buildBackendMenuAction();
        }
    }
    
    protected function returnRenderedView($view)
    {
        $this->response->addOutputByName('lwMembersearchOutput', $view->render());
        return $this->response;
    }
    
    protected function addGbAction()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
            $result = $this->dic->getGbRepository()->saveObject(false, $dataValueObject);
            $this->response->setReloadCmd('showGbList', array('response'=>1));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->addGbFormAction($e->getErrors());
        }
    }
    
    protected function addGbFormAction($errors=false)
    {
        $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
        $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        $formView = new \lwMembersearch\Domain\GB\View\Form('add', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    protected function addFbFormAction($errors=false)
    {
        $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
        $entity = \lwMembersearch\Domain\FB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        $formView = new \lwMembersearch\Domain\FB\View\Form('add', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    protected function addFbAction()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject(array_merge(array("category_id"=>$this->request->getInt("category_id")), $this->request->getPostArray()));
            $result = $this->dic->getFbRepository()->saveObject(false, $dataValueObject);
            $this->response->setReloadCmd('editGbForm', array('response'=>1, 'id'=>$this->request->getInt("category_id")));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->addFbFormAction($e->getErrors());
        }
    }
    
    protected function editFbFormAction($errors=false)
    {
        if ($errors) {
           $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
            $entity = \lwMembersearch\Domain\FB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        }
        else {
            $entity = $this->dic->getFbRepository()->getObjectById($this->request->getInt("id"));
        }
        $formView = new \lwMembersearch\Domain\FB\View\Form('edit', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    protected function saveFbAction()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
            $result = $this->dic->getFbRepository()->saveObject($this->request->getInt("id"), $dataValueObject);
            $this->response->setReloadCmd('editGbForm', array('response'=>1, 'id'=>$this->request->getInt("category_id")));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->editGbFormAction($e->getErrors());
        }
    }    
    
    protected function saveGbAction()
    {
        try {
            $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
            $result = $this->dic->getGbRepository()->saveObject($this->request->getInt("id"), $dataValueObject);
            $this->response->setReloadCmd('showGbList', array('response'=>1));
            return $this->response;
        }
        catch (\LWddd\validationErrorsException $e) {
            return $this->editGbFormAction($e->getErrors());
        }
    }
    
    protected function editGbFormAction($errors=false)
    {
        if ($errors) {
           $dataValueObject = new \LWddd\ValueObject($this->request->getPostArray());
            $entity = \lwMembersearch\Domain\GB\Model\Factory::getInstance()->buildNewObjectFromValueObject($dataValueObject);
        }
        else {
            $entity = $this->dic->getGbRepository()->getObjectById($this->request->getInt("id"));
        }
        $formView = new \lwMembersearch\Domain\GB\View\Form('edit', $entity);
        $formView->setErrors($errors);
        return $this->returnRenderedView($formView);
    }
    
    protected function deleteGbAction()
    {
        try {
            $repository = $this->dic->getGbRepository();
            $ok = $repository->deleteObjectById($this->request->getInt("id"));
            $this->response->setReloadCmd('showGbList', array('response'=>2));
            return $this->response;
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }        
    }
    
    protected function showGbListAction()
    {
        return $this->returnRenderedView(new \lwMembersearch\Domain\GB\View\GbList());
    }
    
    protected function buildBackendMenuAction()
    {
        return $this->returnRenderedView(new \lwMembersearch\View\backendMenu());
    }    
}