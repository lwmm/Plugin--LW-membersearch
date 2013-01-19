<?php

namespace lwMembersearch\Domain\GB\Specification;

if (!defined('FAB_REQUIRED_ERROR')) { define("FAB_REQUIRED_ERROR", "1"); }

class isValid
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "name",
                "opt1text",
                "lw_object",
                "lw_first_date",
                "lw_last_date");        
    }
    
    static public function getInstance()
    {
        return new isValid();
    }
    
    public function isSatisfiedBy(\lwMembersearch\Domain\GB\Object\gb $object)
    {
        $valid = true;
        foreach($this->allowedKeys as $key){
            $method = $key."Validate";
            if (method_exists($this, $method)) {
                $result = $this->$method($this->array[$key]);
                if($result == false){
                    $valid = false;
                }
            }
        }
        return $valid;
    }
}