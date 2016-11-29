<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 29.11.2016
 * Time: 17:24
 */
class Tracks extends Framework
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        header("Location: " . APP_DOMAIN);
    }
}