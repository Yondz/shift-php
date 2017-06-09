<?php

/**
 * Autoloader
 */
 
spl_autoload_register(function ($className) 
{
    $folders = array(
        'Classes',
        'Functions'
    );
    
    foreach($folders as $folder)
    {
        $shortClassName = explode('\\', $className );
        $shortClassName = end($shortClassName);
        $path =  __DIR__."/".$folder."/".$shortClassName . '.php';
        if(file_exists($path))
        {
            include_once $path;
            return;
        }            
    }
});