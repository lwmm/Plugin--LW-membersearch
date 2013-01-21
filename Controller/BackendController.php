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
    
    protected function getDomainFacade($domain)
    {
        $class = "\\lwMembersearch\\Domain\\".$domain."\\Facade";
        $facade = $class::getInstance();
        $facade->setResponse($this->response);
        return $facade;
    }
    
    protected function addGbAction()
    {
        return $this->getDomainFacade("GB")->addGB($this->request->getPostArray());
    }
    
    protected function addGbFormAction($errors=false)
    {
        return $this->getDomainFacade("GB")->getGbAddForm($this->request->getPostArray(), $errors);
    }
    
    protected function deleteGbAction()
    {
        return $this->getDomainFacade("GB")->deleteGbById($this->request->getInt("id"));
    }    
    
    protected function saveGbAction()
    {
        return $this->getDomainFacade("GB")->saveGB($this->request->getInt("id"), $this->request->getPostArray());
    }
    
    protected function editGbFormAction($errors=false)
    {
        return $this->getDomainFacade("GB")->getGbEditForm($this->request->getInt("id"), $this->request->getPostArray(), $errors);
    }    
    
    protected function showGbListAction()
    {
        return $this->getDomainFacade("GB")->showGbList();
    }    
    
    protected function addFbFormAction($errors=false)
    {
        return $this->getDomainFacade("FB")->getFbAddForm($this->request->getPostArray(), $errors);
    }
    
    protected function addFbAction()
    {
        return $this->getDomainFacade("FB")->addFb($this->request->getInt("category_id"), $this->request->getPostArray());
    }
    
    protected function editFbFormAction($errors=false)
    {
        return $this->getDomainFacade("FB")->getFbEditForm($this->request->getInt("id"), $this->request->getPostArray(), $errors);
    }
    
    protected function saveFbAction()
    {
        return $this->getDomainFacade("FB")->saveFb($this->request->getInt("category_id"), $this->request->getInt("id"), $this->request->getPostArray());
    }
    
    protected function deleteFbAction()
    {
        return $this->getDomainFacade("FB")->deleteFbById($this->request->getInt("category_id"), $this->request->getInt("id"));
    }    
    
    protected function buildBackendMenuAction()
    {
        return $this->returnRenderedView(new \lwMembersearch\View\backendMenu());
    }    
}