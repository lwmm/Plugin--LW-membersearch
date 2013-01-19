<?php

namespace lwMembersearch\Domain\GB\View;

class Form
{
    public function __construct($type, $entity)
    {
        $this->gb = $entity;
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
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addGb"));
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"saveGb", "id" => $this->gb->getValueByKey('id')));
            
            if (\lwMembersearch\Domain\GB\Specification\isDeletable::getInstance()->isSatisfiedBy($this->gb)) {
                $this->view->deleteAllowed = true;
                $this->view->deleteUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"deleteGb","id"=>$this->gb->getValueByKey('id')));
            }
        }
        $this->gb->renderView($this->view);
        $this->view->backUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"showGbList"));
        return $this->view->render();
    }
}