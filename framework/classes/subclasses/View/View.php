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
    private $payload = [];
    private $variables = [];
    private $framework;

    public function __construct(&$config, &$framework) {
        $this->framework = $framework;
        return $this;
    }

    public function assign($view, $data = []) {
        $path = APP_ROOT
            .DIRECTORY_SEPARATOR
            ."MyApp"
            .DIRECTORY_SEPARATOR
            ."Views"
            .DIRECTORY_SEPARATOR
            .$view
            .".php";
        if(is_file($path)) {
            $this->payload[] = $path;
            foreach($data as $index => $value) $this->variables[$index] = $value;
            return true;
        }
        else return false;
    }

    public function render() {
        foreach($this->variables as $index => $value) $$index = $value;
        foreach($this->payload as $parse) require($parse);
    }



}