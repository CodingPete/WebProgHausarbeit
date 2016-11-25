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
    public function route() {
        $target_controller = $this->framework->modules->Input->get("c", true);
        $target_function = $this->framework->modules->Input->get("f", true);

        if(!$target_controller) $target_controller = get_class($this->framework);
        if(!$target_function) $target_function = "index";

        $target_controller_path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Controllers"
            .DIRECTORY_SEPARATOR
            .$target_controller
            .".php";

        if(is_file($target_controller_path)) {
            require_once($target_controller_path);
            if(class_exists($target_controller))
                if(method_exists($target_controller, $target_function)) {
                    $reflection = new ReflectionMethod($target_controller, $target_function);
                    if ($reflection->isPublic()) {
                        if ($target_controller == get_class($this->framework)) {
                            $this->framework->$target_function();
                        } else {
                            unset($this->framework);
                            new $target_controller();
                        }
                    }
                }
        }
    }
}