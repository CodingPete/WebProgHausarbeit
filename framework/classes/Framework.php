<?php
defined('APP_ROOT') or exit("kthxbai");

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 10.11.2016
 * Time: 13:51
 */
abstract class Framework
{
    private $config;

    public $modules;

    // Muss von allen erbenden Klassen implementiert werden.
    // Dies ist die Funktion die aufgerufen wird, wenn nur der Controller
    // ohne Zielfunktion aufgerufen wird.
    abstract public function index();

    public function __construct()
    {
        // Hier werden alle Untermodule abgelegt.
        $this->modules = new stdClass();

        // Config einlesen-
        require_once(APP_ROOT
            . DIRECTORY_SEPARATOR
            . 'framework'
            . DIRECTORY_SEPARATOR
            .'config'
            . DIRECTORY_SEPARATOR
            . 'config.php');
        $this->config = new Config();

        // Lade alle Untermodule
        $this->load_modules();

        // Route
        $this->modules->Router->route();

    }

    private function load_modules()
    {
        // Lädt alle in der Config stehenden Untermodule
        foreach ($this->config->load_modules as $module => $load) {
            $path = APP_ROOT
                . DIRECTORY_SEPARATOR
                . 'framework'
                . DIRECTORY_SEPARATOR
                . 'classes'
                . DIRECTORY_SEPARATOR
                . 'subclasses'
                . DIRECTORY_SEPARATOR
                . $module
                . DIRECTORY_SEPARATOR
                . $module . '.php';

            // Wie soll das Untermodul geladen werden?
            switch ($load) {
                // 0 : Nicht laden
                case 0:
                    // Tu nix
                    break;
                // 1 : Laden
                case 1:
                    if (is_file($path)) require_once($path);
                    break;
                // 2 : Laden und instanzieren
                case 2:
                    if (is_file($path)) {
                        require_once($path);
                        $this->modules->$module = new $module($this->config, $this);
                    }
                    break;
            }
        }
    }

    // Hiermit kann der Inhalt eines anderen Controllers aufgerufen werden.
    public function load_content($controller, $function = "") {
        echo file_get_contents(APP_DOMAIN."c=$controller&f=$function");
    }
}