<?php namespace App\prescription\utilities\Exception;

use Exception;
use Illuminate\Support\MessageBag;

abstract class BaseException extends Exception
{
    protected $_errors;
    protected $_previousException;
    protected $_code;
    protected $_message;
    
    protected $_userErrorCode;
    //protected $_userErrorMsg;
    
    public function __construct($errors = null, $userErrorCode = 0, Exception $previous = null) {
        
        //$this->_errors = $this->set_errors($errors);
        $this->set_errors($errors);
        //$this->_code = $code;
        //$this->_message = $message;
        $this->_userErrorCode = $userErrorCode;
        
        if ($previous != null)
        {
            $this->_previousException = $previous;
            $this->_code = $this->_previousException->getCode();
            $this->_message = $this->_previousException->getMessage();
        }
       //parent::__construct($this->_message, $this->_code, $this->_previousException);
       parent::__construct($this->_message, 0, $this->_previousException);
    }
        
    protected function set_errors($errors){
        if (is_string($errors)){
            $errors = array('error' => $errors,);
        }
        
        if (is_array($errors)){
            $errors = new MessageBag($errors);
        }
        
        $this->_errors = $errors;
        
    }
        
    public function getUserErrorCode()
    {
        return $this->_userErrorCode;
    }
       
    public function getPreviousException()
    {
        return $this->_previousException;
    }
    
    public function getErrors()
    {
        return $this->_errors;
    }
    
        
    //abstract function getMessageForCode();
    
    public function getMessageForCode()
    {
        $msgCode = $this->getUserErrorCode();
        $errorMsg = trans('messages.'.$msgCode);
        return $errorMsg;
    }
    
    
}



