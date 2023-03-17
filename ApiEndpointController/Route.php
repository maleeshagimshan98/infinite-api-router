<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * Route object for API Endpoint Controller class
 */

namespace Infinite\ApiRouter;

/**
 * Class representing a route to follow upon defined conditions are met
 */
class Route {

    /**
     * Conditions to evaluate against the HTTP Request
     * if these conditions are matched this reoute is selected
     *
     * @var array
     */
    private $conditions;

    /**
     * callback function
     * this function will be called if this route is matched
     *
     * @var callback
     */
    private $callback;

    /**
     * Indicates if the user should be authenticated, if this route is selected
     *
     * @var boolean
     */
    private $authenticated;

    /**
     * constructor
     *
     * @param array $conditions
     * @param callback $callback
     * @param boolean $authenticate
     */
    public function __construct ($conditions,$callback=false,$authenticated = false)
    {
        $this->conditions = $conditions;
        $this->callback = $callback ? $callback :  $this->get_default_callback();
        $this->authenticated = $authenticated;
    }

    /**
     * default empty callback function for $this->callback
     */
    private function get_default_callback () 
    {
        return function () {
            return;
        };
    }

    /**
     * validate given condition is in the proper form
     *
     * @return void
     */
    private function validateCondition ()
    {

    }

    /**
     * Get the value of Conditions to evaluate against the HTTP Request
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * get number of conditions defined for this route
     *
     * @return integer
     */
    public function getConditionsCount()
    {
        return count($this->conditions);
    }

    /**
     * Set the value of Conditions to evaluate against the HTTP Request
     *
     * @param array $conditions
     *
     * @return self
     */
    public function setConditions(array $conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * Get the value of callback function
     *
     * @return callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Set the value of callback function
     *
     * @param callback $callback
     *
     * @return self
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Get the value of Indicates if the user should be authenticated, if this route is selected
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * Set the value of Indicates if the user should be authenticated, if this route is selected
     *
     * @param boolean $authenticated
     *
     * @return self
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;
        return $this;
    }

}



 ?>
