<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 10.11.2016
 * Time: 13:50
 */
define('APP_ROOT', dirname(__FILE__));
define('APP_DOMAIN', "http://" . $_SERVER["HTTP_HOST"] . "/index.php?");
define('APP_STATICS', "http://" . $_SERVER["HTTP_HOST"] . "/MyApp/Views/statics/");

if (!ini_get('display_errors')) {
    ini_set('display_errors', '0');
}

require_once(APP_ROOT
    . DIRECTORY_SEPARATOR
    . "framework"
    . DIRECTORY_SEPARATOR
    . "classes"
    . DIRECTORY_SEPARATOR
    . "Framework.php");
require_once(APP_ROOT
    . DIRECTORY_SEPARATOR
    . "MyApp"
    . DIRECTORY_SEPARATOR
    . "Controllers"
    . DIRECTORY_SEPARATOR
    . "MyTrack.php");

new MyTrack();