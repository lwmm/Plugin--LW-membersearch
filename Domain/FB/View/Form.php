<?php

namespace lwMembersearch\Domain\FB\View;

class Form
{
    public function __construct($type, $entity)
    {
        $this->dic = new \lwMembersearch\Services\dic();
        $this->object = $entity;
        $this->view = new \lw_view(dirname(__FILE__).'/templates/form.tpl.phtml');
        $this->view->type = $type;
    }
    
    public function setEvent(\LWddd\DomainEvent $event)
    {
        $this->event = $event;
    }
    
    public function setErrors($errors=false)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        $this->event->addEventHistory('used in Form View Renderer ['.__CLASS__.'->'.__FUNCTION__.': '.__LINE__.']');           
        if ($this->view->type == "add") {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addFb", "category_id"=>$this->event->getParameterByKey("categoryId")));
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"saveFb", "id" => $this->object->getId(), "category_id"=>$this->event->getParameterByKey("categoryId")));
            
            if (\lwMembersearch\Domain\FB\Specification\isDeletable::getInstance()->isSatisfiedBy($this->object)) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"deleteFb","id"=>$this->object->getId(), "category_id"=>$this->event->getParameterByKey("categoryId")));
            }
        }
        $this->object->renderView($this->view);
        $this->view->backUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"editGbForm", "id"=>$this->event->getParameterByKey("categoryId")));
        return $this->view->render();
    }
}