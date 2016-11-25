<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 19:37
 */
class Model
{
    private $framework;
    public function __construct(&$config, &$framework) {
        $this->framework = $framework;
    }

    public function load($model) {
        $path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Models"
            .DIRECTORY_SEPARATOR
            .$model
            .".php";
        if(is_file($path)) {
            require_once($path);
            $this->framework->{$model} = new $model();
            $this->framework->$model->Database = $this->framework->modules->Database;
            return true;
        }
        else return false;
    }
}