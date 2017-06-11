<?php

namespace ShiftPHP\Classes;


/**
 * Base client class to execute GET/POST/PUT requests on the API
 * @author Yondz
 * @package ShiftPHP
 * @link https://github.com/yondz/shift-php
 * @license https://github.com/yondz/shift-php/blob/master/LICENSE
 */
class Command {


    protected $route;
    protected $type;
    protected $params;
    protected $data;

    // Making some constants to make this code generic
    const SUCCES_KEY = "success";
    const ERROR_KEY  = "error";

    /**
     * ShiftClient constructor.
     * @param $command
     * @param $type
     */
    public function __construct($host, $command, $type){
        $this->route = $host.$command;
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
        if(!is_null($value)){
            $this->params[$key] = $value;
        }
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
     * Execute the command and returns the response as an array
     * @return mixed
     */
    public function execute(){
        
        // Get cURL resource
        $curl = curl_init($this->getRequestUri());

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->type);
        curl_setopt($curl,CURLOPT_FAILONERROR,true);

        // Adding POST/PUT parameters
        if($this->isPOST() || $this->isPUT()){
            $params = json_encode($this->params);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        // Send the request & save response to $resp
        $response = curl_exec($curl);

        // Curl error
        if($errors = curl_error($curl)){
            curl_close($curl);
            throw new CommandException($errors);
        }

        // Close request to clear up some resources
        curl_close($curl);


        $this->data = json_decode($response, true);

        // If the success key does not exist, we'll consider the request as successful and it'll be up to the user to validate it.
        if(isset($this->data[self::SUCCES_KEY])){
            if(!$this->data[self::SUCCES_KEY]){
                throw new CommandException($this->getError());
            }
        }

        return $this->getData();
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
     * Retrieve the error status(wrapper)
     * @return mixed|null
     */
    public function getError(){
        return $this->getData(self::ERROR_KEY);
    }

    /**
     * Retrieve the success status (wrapper)
     * @return mixed|null
     */
    public function getSuccess(){
        return $this->getData(self::SUCCES_KEY);
    }

}