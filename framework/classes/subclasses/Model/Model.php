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

    // Lädt ein Model und hängt eine Instanz dessen an das Framework an
    public function load($model) {
        $path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Models"
            .DIRECTORY_SEPARATOR
            .$model
            .".php";

        // Wenn die Datei vorhanden ist.
        if(is_file($path)) {
            require_once($path);

            // Instanz vom Model an Framework hängen
            $this->framework->{$model} = new $model();

            // Instanz der Datenbank an das geladene Model hängen.
            $this->framework->$model->Database = $this->framework->modules->Database;
            return true;
        }
        else return false;
    }
}