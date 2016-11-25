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

    public function post($field, $validation) {
        if(isset($_POST[$field])) {
            if(!$validation) return $_POST[$field];
            else return htmlspecialchars(strip_tags($_POST[$field]));
        }
        else return false;
    }

    public function get($field, $validation) {
        if(isset($_GET[$field])) {
            if(!$validation) return $_GET[$field];
            else return htmlspecialchars(strip_tags($_GET[$field]));
        }
        else return false;
    }
}