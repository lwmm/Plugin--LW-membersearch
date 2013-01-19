<?php

namespace lwMembersearch\Domain\GB\View;

class GbList
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
        $this->view = new \lw_view(dirname(__FILE__).'/templates/list.tpl.phtml');
    }
    
    public function render()
    {
        $this->view->gbs = $this->dic->getGbRepository()->getAllObjectsAggregate();
        $this->view->deletableSpecification = \lwMembersearch\Domain\GB\Specification\isValid::getInstance();
        $this->view->backUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"buildBackendMenu"));
        $this->view->newUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addGbForm"));
        return $this->view->render();
    }
}