<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * API Endpoint Controller class
 */

namespace Infinite\ApiRouter;

use \Infinite\ApiRouter\RouteSelector;
use \Infinite\Request;
use \Infinite\ApiRouter\Route;
use \Infinite\ApiRouter\Exceptions\ApiEndpointException;

/**
 * API Endpoint Controller class.
 * controlls the api endpoint
 */
class ApiEndpointController
{
    /**
     * configuration options
     *
     * @var array
     */
    private $props = ["auth","","fallback"];

    /**
     * configuration data
     *
     * @var array
     */
    private $options = [];

    /**
     * chosen set of parameters and callback method
     * depending on parameters passed
     *
     * @var array
     */
    private $selected_routes = [];

    /**
     * All defined routes for the endpoint
     *
     * @var array[\Infinite\ApiRouter\Route]
     */
    private $routes;

    /**
     * All data from request query string, request body
     *
     * @var array
     */
    private $params = [];

    /**
     * Request object containing all data of HTTP Request
     *
     * @var \Infinite\Request
     */
    private $request;

    /**
     * Instance of RouteSelector
     */
    private $routeSelector;

    /**
     * constructor
     *
     * @param $options - options for endpointController
     */
    public function __construct($options)
    {
        $this->initialize($options);
        $this->request = new Request();
        $this->params = array_merge($this->request->getQueryParams(), (array) $this->request->jsonDecodeRequestBody());
        $this->routeSelector = new RouteSelector();
    }

    /**
     * initialize ApiEndpointController with options
     *
     * @param Object $options
     * @return void
     */
    private function initialize($options)
    {
        foreach ($this->props as $key) {
            if (!isset($options["$key"])) {
                continue;
            }
            $this->options[$key] = $options[$key];
        }
    }

    /**
     * initialize Route objects from given route options
     *
     * @param array $routes
     * @return void
     */
    private function initializeRoutes($routeOptions)
    {
        foreach ($routeOptions as $routeOption) {
            $this->routes[] = new Route($routeOption);
        }
    }

    /**
     * match request parameters with the given conditions.
     * store matched items in routes array setting the number of conditions as key
     * 
     * [ [number_of_conditions] => $route ]
     *
     *
     * @return self
     * @throws ApiEndpointException
     */
    private function match()
    {
        foreach ($this->routes as $route) {
            $matched = $this->routeSelector->match_params($this->params, $route->getConditions());
            if ($matched) {
                $this->selected_routes[$route->getConditionsCount()] = $route;
            }
        }
        if (count($this->routes) === 0) {
            throw new ApiEndpointException("No matching route found");
        }
        return $this;
    }

    /**
     * select the route which has maximum matching parameters
     *
     * @return integer
     */
    private function getRouteIndex()
    {
        $max = 0;
        foreach ($this->selected_routes as $key => $val) {
            if ($max < $key) {
                $max = $key;
            }
        }
        return $max;
    }

    /**
     * if authenticated is set to true in options,
     * authenticate the user using callback function provided.
     * request's all parameters (query string/ request body) are passed as argument
     *
     * @return boolean
     */
    protected function authenticate()
    {
        return call_user_func($this->options["auth"], $this->params);
    }

    /**
     * do any authentication needed and
     * execute provided callback function
     * if declared parameters are matched
     *
     * @param Route $route
     * @return mixed
     * @throws \Exception
     */
    public function handleRoute($route)
    {
        /**
         * suggested to remove the authentication, that is not a responsibility of this class 
         */
        if ($route->isAuthenticated()) {
            if (!isset($this->options["auth"])) {
                throw new \Exception("Authentication callback is not defined");
            }
            if (!$this->authenticate()) {
                throw new \Exception("Authentication failed");
            }
        }

        $result = $route->getCallback()((object) $this->params);
        return $result;
    }

    /**
     * handle HTTP GET requests
     *
     * @return void
     */
    public function get ()
    {
        //...
    }

    /**
     * handle HTTP POST requests
     *
     * @return void
     */
    public function post()
    {
        //....
    }

    /**
     * listen to request, selects the best route and execute the callback provided for the route
     *
     * @param array $routes
     * @return mixed
     */
    public function listen($routes)
    {
        $this->initializeRoutes($routes);
        try {
            $route_index = $this->match()->getRouteIndex(); //echo \json_encode($this->routes); die;
            $this->handleRoute($this->routes[$route_index]);
        }
        catch (ApiEndpointException $e) {
            //... in case none of routes matched,
            //... run default route
        }
        catch (\Exception $e) {
            //... in case general exception
            //... run fallback 
        }
    }
}
