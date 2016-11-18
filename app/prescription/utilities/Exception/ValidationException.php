<?php

namespace App\prescription\utilities\Exception;

use App\prescription\utilities\Exception\BaseException;


class ValidationException extends BaseException {

    //protected $errorArray = array();
    protected $validationErrors;
    
    /*public function __construct($messages) 
    {
        $this->$validationErrors = $this->$validationErrors.$messages;
    }*/
    
    public function setErrorMessages($messages)
    {
        $this->validationErrors = $messages;
    }
    
    public function getErrorMessages()
    {
        return $this->validationErrors;
    }
    
    protected function set_errors($errors){
        if (is_string($errors)){
            $errors = array('error' => $errors,);
        }

        if (is_array($errors)){
            $errors = new MessageBag($errors);
        }

        if (isset($this->_errors))
        {
            $validationErrors = $this->_errors;
            $this->_errors = $validationErrors.$errors;
        }
        else
            $this->_errors = $errors;

        $validationErrors = NULL;
        
        return $this;

    }

}