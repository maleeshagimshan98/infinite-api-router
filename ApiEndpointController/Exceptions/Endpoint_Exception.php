<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * API Endpoint Exception class
 */

namespace Infinite\ApiRouter\Exceptions;

class EndpointException extends \Exception
{ //TODO -- extend with Exception class

    public function __constructor($flag, $message)
    {
        $this->code = $flag;
        $this->message = $message;
    }
}
