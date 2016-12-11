<?php
defined('APP_ROOT') or exit("kthxbai");

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 19:14
 */

// Einstiegscontroller der Website
class MyTrack extends Framework
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        // User eingeloggt?
        $login = $this->modules->Session->get("user_type") == "user";

        // Header laden.
        $this->modules->View->assign("header", array(
            "login" => $login
        ));

        // Niemand eingeloggt? Zeige Landingpage
        if (!$login)
            $this->modules->View->assign("Landingpage");
        // Ansonsten lade App-Dashboard
        else $this->dashboard();

        $this->modules->View->assign("footer");
        $this->modules->View->render();
    }


    private function dashboard()
    {
        $this->modules->View->assign("toolbar");
        $this->modules->View->assign("side_bar", array(
            "user_id" => $this->modules->Session->get("user_id")
        ));
        $this->modules->View->assign("content_panel");
        $this->modules->View->assign("map");
    }

}