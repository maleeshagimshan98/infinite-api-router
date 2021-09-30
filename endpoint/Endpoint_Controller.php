<?php
/**
 * © Maleesha Gimshan - 2021 - github.com/maleeshagimshan98
 * endpoint controller class for platform_builder
 */

 namespace EndpointController\Endpoint;
 
 use EndpointController\Exceptions\EndpointException;
 use EndpointController\Endpoint\FunctionSelector;
 use EndpointController\Request;
 use EndpointController\Endpoint\Response\ApiResponse;

class EndpointController
{
    /**
     * instance properties
     *
     * @var array
     */
    private $props = ["auth"];

    /**
     * chosen set of parameters and callback method
     * depending on parameters passed
     *
     * @var array
     */
    public $routes = [];

    public $params = [];
    private $dataAccess;

    /**
     * constructor
     * 
     * @param $options - options for endpointController
     */
    public function __construct ($options)
    {
        foreach($this->props as $key) {
            if (!isset($options["$key"])) {
                throw new \Exception("Invalid_Arguments");
            }
            $this->$key= $options[$key];
        }
                
        $this->request = new request();
        $this->params = array_merge($this->request->params(),(array)$this->request->requestBody());
        $this->functionSelector = new FunctionSelector();
       
    }
    
    /**
     * match request parameters with the given conditions
     * and store matched items in routes array
     * 
     * @param array $conditions
     * @return void
     * @throws \Exception
     */
    public function match ($conditions)
    {
        foreach ($conditions as $condition){
            $condition = $condition;
            $matched = $this->functionSelector->match_params($this->params,$condition["condition"]);
            if ($matched) {
               $this->routes[count($condition["condition"])] = $condition;
            }
        }
        if (count($this->routes) == 0) {
            throw new \Exception("Incomplete_Data");
        } 
        return $this;
    }

    /**
     * select best matching route
     *
     * @return array
     */
    public function selectRoute ()
    {
        $max = 0;
        foreach ($this->routes as $key => $val) {
            if ($max < $key) {
                $max = $key;
            }     
        }
        return $max;
    }


    /**
     * do any authentication needed and
     * execute provided callback function
     * if declared parameters are matched
     * 
     * @param array $conditions
     * @return void
     */
    public function handleRoute ($conditions)
    {
        try {
            if (isset($conditions["authenticated"]) && $conditions["authenticated"]) {
                /*
                 * changed on 26/08/21 to allow provided auth function to use request data freely,
                 * so it can handle authorization in a way it want
                 * changed call_user_func($this->auth,$this->request->requestBody()->user) to $this->request->requestBody();
                 */
                $is_authenticated = call_user_func($this->auth,$this->request->requestBody());
                if (!$is_authenticated) {
                    throw new \Exception("User_Not_Valid");
                }
            }

            $result = $conditions["callback"]((object)$this->params);
            echo \json_encode(new ApiResponse("OK",$result));
            //IMPORTANT - consider exit()
        }
        catch (\Exception $e) {
            $response = new ApiResponse();
            echo \json_encode($response->errorMessage($e->getMessage()));
        }
    }

    /**
     * listen to request and 
     * selects the best route
     * 
     * @param array $options
     * @return void
     */
    public function listen ($options)
    {
        try {
            $route_index = $this->match($options)->selectRoute(); //echo json_encode($this->routes); die;
            $this->handleRoute($this->routes[$route_index]);
        }
        catch (\Exception $e) {
            $response = new ApiResponse();
            echo \json_encode($response->errorMessage($e->getMessage()));
        }
    }
}
