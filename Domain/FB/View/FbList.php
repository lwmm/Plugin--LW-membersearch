<?php

namespace lwMembersearch\Domain\FB\View;

class FbList
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
        $this->view = new \lw_view(dirname(__FILE__).'/templates/list.tpl.phtml');
    }
    
    public function render()
    {
        $this->view->fbs = $this->dic->getFbRepository()->getAllObjectsByCategoryAggregate($this->dic->getLWRequest()->getInt("id"));
        $this->view->deletableSpecification = \lwMembersearch\Domain\FB\Specification\isDeletable::getInstance();
        $this->view->newUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addFbForm", "category_id"=>$this->dic->getLWRequest()->getInt("id")));
        $this->view->categoryId = $this->dic->getLWRequest()->getInt("id");
        return $this->view->render();
    }
}