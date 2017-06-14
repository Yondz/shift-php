<?php

namespace ShiftPHP\Classes;

/**
 * Abstract API class
 * @author Yondz
 * @package ShiftPHP
 * @link https://github.com/yondz/shift-php
 * @license https://github.com/yondz/shift-php/blob/master/LICENSE
 */
abstract class AbstractAPI {

    /**
     * API host
     * @var string $host
     */
    protected $host;

    // Cache configuration
    protected $enableCache    = false;
    protected $cacheLifetime  = 300;
    protected $cacheFolder    = "cache";

    /**
     * API constructor
     */
    public function __construct($host){
        $this->host =  $host;
    }


    /**
     * Enable command caching
     */
    public function enableCache(){
        $this->enableCache = true;
    }

    /**
     * Disable command caching
     */
    public function disableCache(){
        $this->enableCache = false;
    }

    /**
     * Set cache lifetime
     * @param $lifetime
     */
    public function setCacheLifetime($lifetime){
        $this->cacheLifetime = $lifetime;
    }

    /**
     * Set cache folder
     * @param $lifetime
     */
    public function setCacheFolder($folder){
        $this->cacheFolder = $folder;
    }

}