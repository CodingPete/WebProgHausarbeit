<?php
defined('APP_ROOT') or exit("kthxbai");
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 19:38
 */
class View
{
    // Hier werden die Views abgelegt.
    private $payload = [];
    // Hier werden die Variablen der Views abgelegt.
    private $variables = [];
    private $framework;

    public function __construct(&$config, &$framework) {
        $this->framework = $framework;
        return $this;
    }

    // Lädt den Inhalt einer View
    public function assign($view, $data = []) {
        $path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Views"
            .DIRECTORY_SEPARATOR
            .$view
            .".php";

        // Wenn die View-Datei existiert, ...
        if(is_file($path)) {

            // ... Pfad zu dieser Datei für den Renderer ablegen.
            $this->payload[] = $path;
            // ... und jede Variable für den Renderer ablegen.
            foreach($data as $index => $value) $this->variables[$index] = $value;
            return true;
        }
        else return false;
    }

    // Baut die Views auf
    public function render() {

        // Jede Variable laden.
        foreach($this->variables as $index => $value) $$index = $value;
        // Jede View laden.
        foreach($this->payload as $parse) require($parse);
    }



}