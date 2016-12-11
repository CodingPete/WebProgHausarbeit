<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 22:04
 */
class Session
{
    // Falls noch keine Session existiert, eine starten.
    public function __construct(&$config, &$framework) {
        if(session_status() == PHP_SESSION_NONE)
            session_start();

        return $this;
    }

    // Variable aus der Session holen.
    public function get($what) {
        if(isset($_SESSION[$what])) return $_SESSION[$what];
        else return false;
    }

    // Variable in die Session schreiben.
    public function set($what, $value) {
        $_SESSION[$what] = $value;
    }

    // Sesseion zerstören.
    public function destroy() {
        session_destroy();
    }
}