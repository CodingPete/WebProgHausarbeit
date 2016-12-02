<?php
defined('APP_ROOT') or exit("kthxbai");

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 19:14
 */
class MyTrack extends Framework
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->modules->View->assign("header");
        $this->modules->View->assign("toolbar");
        if ($this->modules->Session->get("user_type") != "user") {

            $this->modules->View->assign("Landingpage", array(
                "login_link" => APP_DOMAIN . "c=Users&f=login",
                "register_link" => APP_DOMAIN . "c=Users&f=register"
            ));
        } else $this->dashboard();

        $this->modules->View->assign("footer");
        $this->modules->View->render();
    }


    private function dashboard()
    {
        $this->modules->View->assign("side_bar");
        $this->modules->View->assign("map");
    }

}