<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * route selector class for endpoint controller
 */

namespace Infinite\ApiRouter;

/**
 * Class for selecting the matching route from the given routes
 */
class RouteSelector
{

    /**
     * constructor
     */
    public function __construct()
    {
    }

    /**
     * check if array is associative
     *
     * @param array
     * @return boolean
     */
    protected function is_assoc($arr)
    {
        if (is_array($arr) && count($arr) == 0) {
            return false;
        }
        if (is_array($arr) && array_keys($arr) !== range(0, count($arr) - 1)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * checks if the request parameter's property value equals
     * to a value from given single value or an array of values/ or simply any value.
     *
     * @param string $parameter - request's paramter
     * @param array|string $value - condition's accepted value
     * @return boolean
     */
    public function match_values($parameter, $value)
    {
        if (is_array($value) && !$this->is_assoc($value)) {
            $is_matched = false;

            /**
             * Allow any of values for the condition
             */
            if (count($value) == 0) {
                $is_matched = true;
                return $is_matched;
            }

            foreach ($value as $prop) {
                if ($parameter == $prop) {
                    $is_matched = true;
                    break;
                }
            }
            return $is_matched;
        }
        return $parameter == $value ? true : false;
    }

    /**
     * check if given property in the request parameter exists and is not empty.
     *
     * @param array $parameters
     * @param string $prop
     * @return boolean
     */
    private function isPropAvailable($parameters, $prop)
    {
        return !isset($parameters[$prop]) || empty($parameters[$prop]);
    }

    /**
     * match request's parameters with given values.
     * this function returns true only if all of the specified conditions are mt in the request parameters
     *
     * @param object $parameters - request's all parameters (query string, request body)
     * @param mixed $conditions - required conditions
     * @return boolean
     */
    public function match_params($parameters, $conditions)
    {
        $matched = true;
        foreach ($conditions as $prop => $value) {
            /**
             * if specified property is not available in the request, exit from the loop
             */
            if (!$this->isPropAvailable($parameters, $prop)) {
                $matched = false;
                break;
            }

            /**
             * if given condition's value is an associative array
             * and the request's respective parameter is and object.
             * if condition has nested properties, recursively checks the nested properties in the request
             */
            if (is_array($value) && $this->is_assoc($value) && is_object($parameters->$prop)) {
                $matched = $this->match_params($parameters->$prop, $value);
                if (!$matched) {
                    /**
                     * Critical - if any of the conditions in the route
                     * does not match, skip the rest of conditions for the route
                     * and exit loop
                     */
                    break;
                }
                continue;
            }
            $matched = $this->match_values($parameters->$prop ?? null, $value);

            if (!$matched) {
                //$matched = false;
                /**
                 * Critical - if any of the conditions in the route
                 * does not match, skip the rest of conditions for the route
                 * and exit loop
                 */
                break;
            }
        }
        return $matched;
    }

}
