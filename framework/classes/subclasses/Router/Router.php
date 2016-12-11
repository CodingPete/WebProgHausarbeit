<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 23:00
 */
class Router
{
    private $framework;
    public function __construct(&$config, &$framework) {
        $this->framework = $framework;
        return $this;
    }

    // Wertet die GET-Parameter aus um zu gucken, welcher Controller mit
    // welcher Funktion aufgerufen werden soll.
    public function route() {

        $target_controller = $this->framework->modules->Input->get("c", true);
        $target_function = $this->framework->modules->Input->get("f", true);

        // Wenn kein Zielcontroller vorliegt, den aktuellen instanzieren.
        if(!$target_controller) $target_controller = get_class($this->framework);
        // Wenn keine Zielfunktion vorliegt, die Indexfunktion aufrufen
        if(!$target_function) $target_function = "index";

        // Pfad zur Datei
        $target_controller_path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Controllers"
            .DIRECTORY_SEPARATOR
            .$target_controller
            .".php";

        // Wenn die Datei des Controllers existiert, ...
        if(is_file($target_controller_path)) {
            require_once($target_controller_path);
            // ...eine gültige Klasse vorliegt
            if(class_exists($target_controller))
                // ... die Zielfunktion im Zielcontroler existiert
                if(method_exists($target_controller, $target_function)) {
                    // dann prüfen ob sie public ist.
                    $reflection = new ReflectionMethod($target_controller, $target_function);

                    // Wenn public, ...
                    if ($reflection->isPublic()) {
                        // Wenn der Zielcontroller der Aktuelle ist, ...
                        if ($target_controller == get_class($this->framework)) {
                            // Zielfunktion in diesem Controller aufrufen.
                            $this->framework->$target_function();
                        }
                        else {
                            // Aktuellen Controller zerstören.
                            unset($this->framework);
                            // Zielcontroller laden.
                            new $target_controller();
                        }
                    }
                }
        }
    }
}