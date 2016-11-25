<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 12.11.2016
 * Time: 12:15
 */
class GPS extends Framework
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->modules->View->assign("GPS");
        $this->modules->View->render();
    }
}
