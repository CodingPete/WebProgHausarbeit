<?php
defined('APP_ROOT') or exit("kthxbai");

class Config {

    /*
     * Welche Module sollen geladen werden?
     * 0 : Nicht laden
     * 1 : Laden
     * 2 : Laden und Instanzieren.
     */
    public $load_modules = array(
        "Database" => 2,
        "Input" => 2,
        "Model" => 2,
        "View" => 2,
        "Session" => 2,
        "Router" => 2
    );

    // todo: Authentifizierung implementieren
    public $redis_credentials = array(
        "host" => "localhost",
        "port" => "6379",
        "user" => "",
        "pass" => ""
    );

    public $redis_db = PETER_MEYER_DB;
    //public $redis_db = 0;

    public function __construct() {
        return $this;
    }
}