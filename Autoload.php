<?php

namespace ShiftPHP;

/**
 * Class Autoload
 * @package ShiftPHP
 * @author Yondz
 * @link https://github.com/yondz/shift-php
 * @license https://github.com/yondz/shift-php/blob/master/LICENSE
 */
class Autoload{

    /**
     * File extension to autoload
     */
    const EXT = "php";

    /**
     * Autoloader
     * @param $className
     */
    public static function loader($className){
        
        $path = $className;
        $path = str_replace(__NAMESPACE__, "", $path);
        $path = str_replace("\\", "/", $path);
        $path = __DIR__ . $path .".". self::EXT;

        if(file_exists($path)){
            include_once $path;
        }
        return;
    }
}


spl_autoload_register('ShiftPHP\Autoload::loader');