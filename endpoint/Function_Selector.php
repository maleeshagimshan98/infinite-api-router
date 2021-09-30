<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * route parameter class for endpoint controller
 */

 namespace EndpointController\Endpoint;

 class FunctionSelector
 {

    /**
     * constructor
     */
    public function __construct ()
    {
    }

    /**
     * check if array is associative
     * 
     * @param array
     * @return boolean
     */
    protected function is_assoc ($arr)
    {
        if (is_array($arr) && count($arr) == 0) {
            return false;
        }
        if (is_array($arr) && array_keys($arr) !== range(0,count($arr) -1)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * checks if a parameter is present in the request
     * 
     * @param array $parameters - requests's parameter
     * @param object $condition - object containing conditions
     * 
     * @return boolean
     * @throws EndpointExeption
     */
    public function checkParams ($parameters,$conditions)
    {
        $parameters = (object) $parameters;
        foreach ($conditions as $key) {
            if(!isset($parameters->$key)) {
                throw new \Exception("Incomplete_Parameters"); //TODO -- create EndpointException
            }/*
            if (\is_array($value) && $this->is_assoc($value)) {
                $this->checkParams($parameters->$key,$value);                
            }*/
        }
        return true;    
    }    

    /**
     * checks if given variable value equals
     * to a value or array of values
     * 
     * @param string $parameter - request's paramter
     * @param array|string $property - required property
     * @return boolean
     */
    public function match_values ($parameter,$property)
    { 
        if (is_array($property) && !$this->is_assoc($property)) {
            $is_matched = false;
            if (count($property) == 0) {
                $is_matched =  true;
            }
            foreach ($property as $prop) {
                if ($parameter == $prop) {
                    $is_matched = true;
                    break;
                }
            }
            return $is_matched;
        }
        if ($parameter === $property) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * match request's parameters with given values
     * @param string|array $parameter - request's parameters
     * 
     * @param mixed $properties - required properties
     * @return boolean
     */
    public function match_params ($parameter,$properties)
    { 
        $parameter = (object) $parameter;
        $matched = true;    
        foreach ($properties as $prop => $value) {
            if (!isset($parameter->$prop) || empty($parameter->$prop)) { //IMPORTANT - CHECK empty()
                $matched = false;
                break;
            }
            if (is_array($value) && $this->is_assoc($value) && is_object($parameter->$prop)) {
                $matched = $this->match_params($parameter->$prop,$value);
                if (!$matched) {
                    break;
                }
                continue;
            }
            $matched = $this->match_values($parameter->$prop ?? null,$value);
            
            if (!$matched) {
                //$matched = false;
                break;
            }
        } 
        return $matched;
    }

 }


 ?>