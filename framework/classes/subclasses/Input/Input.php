<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 00:49
 */
class Input
{
    public function __construct(&$config, &$framework) {
        return $this;
    }

    // Post-Parameter holen
    public function post($field, $validation) {

        // Wenn es das gewünschte Feld gibt,...
        if(isset($_POST[$field])) {

            // ... Inhalt ausgeben oder ggf. bereinigen.
            if(!$validation) return $_POST[$field];
            else return htmlspecialchars(strip_tags($_POST[$field]));
        }
        else return false;
    }


    // Get-Parameter holen
    public function get($field, $validation) {

        // Wenn es das gewünschte Feld gibt...
        if(isset($_GET[$field])) {

            // ... Inhalt ausgeben oder ggf. bereinigen.
            if(!$validation) return $_GET[$field];
            else return htmlspecialchars(strip_tags($_GET[$field]));
        }
        else return false;
    }
}