<?php

namespace lwMembersearch\Domain;

class DomainEventDispatch 
{
    public function __construct()
    {
        
    }
    
    public static function getinstance()
    {
        return new DomainEventDispatch();
    }
    
    public function execute($event)
    {
        $DomainEventHandlerClass = "\\lwMembersearch\\Domain\\".$event->getDomainName()."\\EventHandler";
        return $DomainEventHandlerClass::getInstance()->execute($event);
    }
}