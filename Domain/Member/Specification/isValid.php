<?php

namespace lwMembersearch\Domain\Member\Specification;

define("LW_REQUIRED_ERROR", "1");
define("LW_MAXLENGTH_ERROR", "2");
define("LW_EMAIL_ERROR", "5");

class isValid extends \LWddd\Validator
{
    public function __construct()
    {
        $this->allowedKeys = array(
                  "id",
                  "firstname",  #required
                  "lastname",   #required
                  "email",      #required
                  "building",
                  "room",
                  "phone",
                  "fax",
                  "location",
                  "department",
                  "intern",
                  "lw_first_date",
                  "lw_last_date");        
    }
    
    static public function getInstance()
    {
        return new isValid();
    }
    
    public function isSatisfiedBy(\lwMembersearch\Domain\Member\Object\member $object)
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
    
    public function firstnameValidate($key, $object)
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
    
    public function lastnameValidate($key, $object)
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
    
    public function emailValidate($key, $object)
    {
        $bool = true;
        if(!$this->isRequired($object->getValueByKey($key))){
            $this->addError($key, LW_REQUIRED_ERROR);
            $bool = false;
        }
        
        if (!$this->isEmail($object->getValueByKey($key)) ) {
            $this->addError($key, LW_EMAIL_ERROR);
            $bool = false;
        }
        if(!$bool){
            return false;
        }
        return true;
    } 
    
    public function buildingValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    public function roomValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    public function phoneValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    public function faxValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    public function locationValidate($key, $object)
    {
        $maxlength = 20;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    } 
    
    public function resetErrors()
    {
        \LWddd\Validator::resetErrors();
    }
}