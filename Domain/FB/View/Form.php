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
    
    public function setErrors($errors=false)
    {
        $this->view->errors = $errors;
    }
    
    public function render()
    {
        if ($this->view->type == "add") {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addFb", "category_id"=>$this->dic->getLWRequest()->getInt("category_id")));
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"saveFb", "id" => $this->object->getValueByKey('id'), "category_id"=>$this->dic->getLWRequest()->getInt("category_id")));
            
            if (\lwMembersearch\Domain\FB\Specification\isDeletable::getInstance()->isSatisfiedBy($this->object)) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"deleteFb","id"=>$this->object->getValueByKey('id'), "category_id"=>$this->dic->getLWRequest()->getInt("category_id")));
            }
        }
        $this->object->renderView($this->view);
        $this->view->backUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"showFbList", "category_id"=>$this->dic->getLWRequest()->getInt("category_id")));
        return $this->view->render();
    }
}