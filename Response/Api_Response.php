<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * Class for api endpoint response
 */

namespace EndpointController\Endpoint\Response;

/**
 * ApiResponse class
 */
class ApiResponse
{

    /**
     * constructor
     *
     * @param string $status
     * @param [type] $response
     */
    public function __construct ($status = "OK",$response = null)
    {        
        $this->status = $status;
        $this->response = $response;
        return $this;
    }

    /**
     * Error message
     *
     * @param string $error
     * @param  $data
     * @return array
     */
    public function errorMessage ($error,$data = null)
    {
        $this->status = "error";
        switch ($error) {
            case "Invalid_Credentials" :
                $this->err  = "Email or password does not match with our records";                
            break;

            case "Email_Not_Valid" :
                $this->err = "Email does not match with our records";
            break;

            case "User_Not_Valid" : 
                $this->err = "You have to be logged in to perform this action";
            break;

            case "User_Exists" :
                $this->err = "Email already exists";
            break;

            case "Password_error" :
                $this->err = "Password does not match with records";
            break;

            case "Incomplete_Data" :                
                $this->err = "Incomplete data";
            break;

            case "Invalid_Data" :
                $this->err = "Invalid data";
            break;

            case "User_Not_Permitted" : 
                $this->err = "You do not have permission to perform this action";
            break;

            case "Database_Query_Failed" :
                $this->err = "Internal Error";
            break;

            case "Database_Connection_Failed" : //REMOVE THIS IN PRODUCTION
                $this->err = "Database Error";
            break;

            default :
                $this->err = "Sorry, Unexpected error occured";
            //$this->err(new feedback("$this->error",$this->error));
            break;
        } //echo $error;
        //$this->response = $this->err;
        return [
            "status" => $this->status,
            "message" => $this->err,
            "response" => $this->response
        ];
    }
}
?>