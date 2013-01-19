<?php

class lw_membersearch extends lw_plugin
{
    public function __construct()
    {
        parent::__construct();
        include_once(dirname(__FILE__).'/Services/Autoloader.php');
        $autoloader = new \lwMemberSearch\Services\Autoloader();
        $autoloader->setConfig($this->config);
    }
    
    public function buildPageOutput()
    {
        $response = \lwMembersearch\Services\Response::getInstance();
        
        if ($this->params['module'] == "backend") {
            $controller = new \lwMembersearch\Controller\BackendController($response);
        }
        elseif ($this->params['module'] == "websiteSearch") {
            $controller = new \lwMembersearch\Controller\WebsiteSearchController($response);
        }
        elseif ($this->params['module'] == "intranetSearch") {
            $controller = new \lwMembersearch\Controller\IntranetSearchController($response);
        }
        else {
            die("Module: ".$this->params['module']);
        }
        $response = $controller->execute();
        if ($response->hasReloadCommand()) {
            $url = lw_page::getInstance()->getUrl($response->getReloadCommandWithParameters());
            $this->pageReload($url);
        }
        else {
            return $response->getOutputByName('lwMembersearchOutput');
        }
    }
}