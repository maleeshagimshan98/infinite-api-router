<?php
namespace EndpointController\Exceptions;
 class EndpointException extends \Exception { //TODO -- extend with Exception class

    public function __constructor ($flag,$message) {
        $this->code = $flag;
        $this->message = $message;  
    }       
 }
?>