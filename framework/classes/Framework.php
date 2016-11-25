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

    abstract public function index();

    public function __construct()
    {
        $this->modules = new stdClass();

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

    public function load_content($controller, $function = "") {
        echo file_get_contents(APP_DOMAIN."c=$controller&f=$function");
    }
}