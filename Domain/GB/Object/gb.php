<?php

namespace lwMembersearch\Domain\GB\Object;

class gb extends \LWddd\Entity
{
    public function __construct($id=false)
    {
        parent::__construct($id);
    }
    
    public function renderView($view)
    {
        $view->entity = $this->getValues();
    }    
}