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
    
    /**
     * Validation of given and allowedKeys
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
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
    
    /**
     * firstname validation -> required check and maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
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
    
    /**
     * lastname validation -> required check and maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
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
    
    /**
     * email validation -> required check and isemail check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
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
    
    /**
     * building validation -> maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
    public function buildingValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    /**
     * room validation -> maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
    public function roomValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    /**
     * phone validation -> maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
    public function phoneValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    /**
     * fax validation -> maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
    public function faxValidate($key, $object)
    {
        $maxlength = 255;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    }
    
    /**
     * location validation -> maxlength check
     * @param arraykey $key
     * @param \lwMembersearch\Domain\Member\Object\member $object
     * @return boolean
     */
    public function locationValidate($key, $object)
    {
        $maxlength = 20;
        if (!$this->hasMaxlength($object->getValueByKey($key), array("maxlength"=>$maxlength)) ) {
            $this->addError($key, LW_MAXLENGTH_ERROR, array("maxlength"=>$maxlength));
            return false;
        }
        return true;
    } 
    
    /**
     * reset error array
     */
    public function resetErrors()
    {
        \LWddd\Validator::resetErrors();
    }
}