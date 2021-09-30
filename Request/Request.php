<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * router class for platform_builder
 */
namespace EndpointController;

/**
 * Undocumented class
 */
class Request 
{
    /**
     * request method
     *
     * @var string
     */
    public $method;

    /**
     * request parameters
     *
     * @var object|array
     */
    public $params;

    /**
     * request path
     *
     * @var string
     */
    public $path;

    /**
     * request body
     *
     * @var object
     */
    public $requestBody;

    /**
     * constructor
     */
    public function __construct ()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = $this->getRequestPath();
        parse_str($_SERVER['QUERY_STRING'],$this->params);
        
        $this->requestBody = $this->parseRequestBody();
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
     * get request body
     * 
     * @param string $param_name - parameter name
     * @return object|string
     */
    public function requestBody ($param_name = null)
    {
        if (
            isset($param_name)&&
            $param_name !== ""&&
            isset($this->requestBody->$param_name))
            {
              return $this->requestBody->$param_name;
            }

        return $this->requestBody;
    }

    /**
     * get request's path
     * 
     * @return string
     */
    public function path ()
    {
        return $this->path;
    }

    /**
     * get request's query parameters
     * 
     * @param string $param_name - parameter name
     * @return array|string
     */
    public function params ($param_name = null)
    {
        if (
            isset($param_name)&&
            $param_name !== "" &&
            isset($this->params[$param_name]))
            {
                return $this->params->$param_name;
            }
        return $this->params;
    }

    /**
     * get request parameter by name
     * example :-  {method : 'POST', name : 'task'}
     * 
     * @param object $param - parameter data     * 
     * @return string
     */
    public function getParam ($param)
    {
        switch ($param->method) {
            case "POST" :
                if (isset($this->requestBody[$param->name])) {
                    if (gettype($param->name) === "array") {
                        
                    }
                    return $this->requestBody[$param->name];
                }
                return false;
            break;

            case "GET" :
                if (isset($this->params[$param->name])) {
                    return $this->params[$param->name];
                }
                return false;
            break;
        }        
    }

    /**
     * parse request body
     * if request method is POST
     * 
     * @return array|object - json decoded object
     */
    public function parseRequestBody ()
    {
        if ($this->method == "POST"){
            return json_decode(rawurldecode(file_get_contents("php://input")));
        }
    }

    /**
     * get requests path
     *
     * @return string
     */
    public function getRequestPath ()
    {
        $path = parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH);
        $name = explode(".php",$path);
        return $name[0];
    }
}

 ?>