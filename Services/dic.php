<?php

namespace lwMembersearch\Services;

class dic
{
    public function __construct()
    {
        
    }
    
    public function getGbRepository()
    {
        if (!$this->GbRepository) {
            $this->GbRepository = new \lwMembersearch\Domain\GB\Model\Repository();
        }
        return $this->GbRepository;        
    }
  
    public function getFbRepository()
    {
        if (!$this->FbRepository) {
            $this->FbRepository = new \lwMembersearch\Domain\FB\Model\Repository();
        }
        return $this->FbRepository;        
    }
  
    public function getDbObject()
    {
        return \lw_registry::getInstance()->getEntry("db");
    }
    
    public function getConfiguration()
    {
        return \lw_registry::getInstance()->getEntry("config");
    }
    
    public function getLwResponse()
    {
        return \lw_registry::getInstance()->getEntry("response");
    }
    
    public function getLwRequest()
    {
        return \lw_registry::getInstance()->getEntry("request");
    }
}