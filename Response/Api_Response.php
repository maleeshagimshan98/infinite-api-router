<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * Class for api endpoint response
 */

namespace Infinite\Response;

/**
 * ApiResponse class
 */
class ApiResponse
{

    /**
     * status of the response
     *
     * @var string
     */
    private $status;

    /**
     * response data
     *
     * @var array|object
     */
    private $response;

    /**
     * error message
     *
     * @var string
     */
    private $message;

    /**
     * constructor
     *
     * @param string $status
     * @param array|object $response
     */
    public function __construct ($status = "OK",$response = [], $message="")
    {        
        $this->status = $status;
        $this->response = $response;
        $this->message = $message;
    }

    /**
     * Get response as an associated array
     *
     * @return array
     */
    public function toArray ()
    {
        return [
            "status" => $this->status,
            "message" => $this->message,
            "response" => $this->response
        ];
    }

    /**
     * Get status of the response
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status of the response
     *
     * @param  string  $status  status of the response
     *
     * @return  self
     */ 
    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get response data
     *
     * @return  array|object
     */ 
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response data
     *
     * @param  array|object  $response  response data
     *
     * @return  self
     */ 
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get error message
     *
     * @return  string
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set error message
     *
     * @param  string  $message  error message
     *
     * @return  self
     */ 
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }
}
?>