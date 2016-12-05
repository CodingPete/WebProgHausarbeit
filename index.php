<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 10.11.2016
 * Time: 13:50
 */

// Redis für Borsti
//require_once 'C:/RedisDBs/RedisDBs_WS16.php';

define('APP_ROOT', dirname(__FILE__));
define('APP_DOMAIN', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
define('APP_STATICS', APP_DOMAIN .  "/MyApp/Views/statics/");

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