<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * request class for managing HTTP Requests
 */
namespace Infinite;

use Exception;

/**
 * class for managing HTTP Request
 */
class Request 
{
    /**
     * request method
     *
     * @var string
     */
    private $method;

    /**
     * request parameters
     *
     * @var array
     */
    private $queryParams;

    /**
     * request path
     *
     * @var string
     */
    private $path;

    /**
     * 
     */
    private $uri;

    /**
     * request body
     *
     * @var string
     */
    private $requestBody;

    /**
     * constructor
     */
    public function __construct ()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->uri = $this->parseRequestUri();
        $this->parseQueryString();              
        $this->requestBody = $this->parseRequestBody();
    }

    /**
     * get request's path
     *
     * @return string
     */
    protected function parseRequestUri ()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"]);
        return $uri;
    }

    /**
     * parse request's query string data
     *
     * @return void
     */
    protected function parseQueryString ()
    {
        parse_str($_SERVER['QUERY_STRING'],$this->queryParams);  
    }

    /**
     * parse request body
     * 
     * @return array|object - request body
     */
    protected function parseRequestBody ()
    {        
        return file_get_contents("php://input");
    }

    /**
     * get the request method
     * 
     * @return string $this->method
     */
    public function getMethod ()
    {
        return $this->method;
    }

    /**
     * get the request's path
     * 
     * @return string $this->uri
     */
    public function getPath ()
    {
        return $this->uri;
    }

    /**
     * get request's body
     * 
     * @return object
     */
    public function requestBody ()
    {
        return $this->requestBody;
    }
    
    /**
     * decode and get the json encoded request body
     *
     * @return object|array
     */
    public function jsonDecodeRequestBody () //***** check - has contradiction with ApiEndpointController->__construct */
    {
        try {
            //... throw errors if json decode fails
            return json_decode($this->requestBody);
        }
        catch (\Exception $e) {
            throw new \Exception("Cannot json decode the request body");
        }
    }

    /**
     * get request's query parameters
     * 
     * @return array
     */
    public function getQueryParams ()
    {        
        return $this->queryParams;
    }
}

 ?>