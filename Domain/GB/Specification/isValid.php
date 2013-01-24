<?php

namespace lwMembersearch\Domain\GB\Specification;

define("LW_REQUIRED_ERROR", "1");
define("LW_MAXLENGTH_ERROR", "2");

class isValid extends \LWddd\Validator
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
    
    /**
     * Validation of given and allowedKeys
     * @param \lwMembersearch\Domain\GB\Object\gb $object
     * @return boolean
     */
    public function isSatisfiedBy(\lwMembersearch\Domain\GB\Object\gb $object)
    {
        $valid = true;
        foreach($this->allowedKeys as $key){
            $method = $key."Validate";
            if (method_exists($this, $method)) {
                $result = $this->$method($key, $object);
                if($result == false){
                    $valid = false;
                }
            }
        }
        return $valid;
    }
    
    /**
     * Name validation -> required check and maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\GB\Object\gb $object
     * @return boolean
     */
    public function nameValidate($key, $object)
    {
        $bool = true;
        if(!$this->isRequired($object->getValueByKey($key))){
            $this->addError($key, LW_REQUIRED_ERROR);
            $bool = false;
        }
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            $bool = false;
        }
        if(!$bool){
            return false;
        }
        return true;
    }
    
    /**
     * opt1text validation -> required check and maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\GB\Object\gb $object
     * @return boolean
     */
    public function opt1textValidate($key, $object)
    {
        $bool = true;
        if(!$this->isRequired($object->getValueByKey($key))){
            $this->addError($key, LW_REQUIRED_ERROR);
            $bool = false;
        }
        $maxlength = 10;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            $bool = false;
        }
        if(!$bool){
            return false;
        }
        return true;
    }   
    
    /**
     * reset of error array
     */
    public function resetErrors()
    {
        \LWddd\Validator::resetErrors();
    }
}