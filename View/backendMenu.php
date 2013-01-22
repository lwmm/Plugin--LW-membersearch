<?php

namespace lwMembersearch\View;

class backendMenu
{
    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__).'/templates/backendMenu.tpl.phtml');
    }
    
    public function render()
    {
        $this->view->maUrl = \lw_page::getInstance()->getUrl(array('cmd'=>"showMaList"));
        $this->view->gbUrl = \lw_page::getInstance()->getUrl(array('cmd'=>"showGbList"));
        $this->view->ldapUrl = \lw_page::getInstance()->getUrl(array('cmd'=>"showLDAPImporter"));
        return $this->view->render();
    }
}