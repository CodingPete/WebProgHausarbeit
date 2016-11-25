<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 22:04
 */
class Session
{
    public function __construct(&$config, &$framework) {
        session_start();
        return $this;
    }
    public function get($what) {
        if(isset($_SESSION[$what])) return $_SESSION[$what];
        else return false;
    }

    public function set($what, $value) {
        $_SESSION[$what] = $value;
    }

    public function destroy() {
        session_destroy();
    }
}