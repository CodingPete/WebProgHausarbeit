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
        $this->modules->View->assign("toolbar", array(
                "logged_in" => $this->modules->Session->get("user_type") == "user"
            )
        );
        if ($this->modules->Session->get("user_type") != "user")
            $this->modules->View->assign("Landingpage");
        else $this->dashboard();

        $this->modules->View->assign("footer");
        $this->modules->View->render();
    }


    private function dashboard()
    {
        $this->modules->View->assign("side_bar", array(
            "user_id" => $this->modules->Session->get("user_id")
        ));
        $this->modules->View->assign("map");
    }

}