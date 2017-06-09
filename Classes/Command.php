<?php

namespace ShiftWalletMonitor\Classes;


/**
 * Base client class to execute GET/POST/PUT requests on the API
 */
class Command {

    protected $host = "https://wallet.shiftnrg.org";
    protected $route;
    protected $params;
    protected $type;
    protected $data;

    /**
     * ShiftClient constructor.
     * @param $command
     * @param $type
     */
    public function __construct($command, $type){
        $this->route = $this->host.$command;
        $this->type = $type;
        $this->success = 0;
        $this->errors = [];
        $this->data = [];
        $this->params = [];
    }

    /**
     * Add an option named $key with data $value
     * @param $key
     * @param $value
     */
    public function setParam($key, $value){
        $this->params[$key] = $value;
    }

    /**
     * Get an option by key
     * @param $key
     * @return mixed|null
     */
    public function getParam($key){
        if(isset($this->params[$key])){
            return $this->params[$key];
        }
        return null;
    }

    /**
     * Get all options
     * @return array
     */
    public function getParams(){
        return $this->params;
    }

    /**
     * Check if the command is GET type
     * @return bool
     */
    public function isGET(){
        return $this->type === "GET";
    }

    /**
     * Check if the command is POST type
     * @return bool
     */
    public function isPOST(){
        return $this->type == "POST";
    }

    /**
     * Check if the command is PUT type
     * @return bool
     */
    public function isPUT(){
        return $this->type == "PUT";
    }

    /**
     * Generate request url
     * @return string
     */
    public function getRequestUri(){
        $uri = $this->route;
        
        // Adding GET parameters if needed
        if($this->isGET()){
            $first = true;
            foreach($this->params as $key => $value){
                $uri .= $first ? "?" : "&";
                $uri .= $key . "=" . $value;
                $first = false;
            }
        }
        return $uri;
    }

    /**
     * Retrieve the command response
     * @return mixed
     */
    public function getData($key = null){
        if(null === $key){
            return $this->data;
        }

        if(isset($this->data[$key])){
            return $this->data[$key];
        }

        return null;
    }
    /**
     * Execute the command and returns the response as an array
     * @return mixed
     */
    public function execute(){
        
        // Get cURL resource
        $curl = curl_init($this->getRequestUri());

        if($this->isPOST()){
            $params = json_encode($this->params);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        } else if($this->isGET()){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            
        }
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        
        
        $this->data = json_decode($response, true);
        return $this->data["success"];
    }

}