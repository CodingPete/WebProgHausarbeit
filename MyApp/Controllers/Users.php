<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 23:45
 */
class Users extends Framework
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $this->modules->Model->load("Users_Model");

        $username = $this->modules->Input->post("username", true);
        $password = $this->modules->Input->post("password", true);

        if($this->Users_Model->login($username, $password)) {
            $this->modules->Session->set("user_type", "user");
            $this->modules->Session->set("user_id", $username);
            exit("true");
        }
        else exit("false");
    }

    public function register() {

        $this->modules->Model->load("Users_Model");

        $username = $this->modules->Input->post("username", true);
        $password = $this->modules->Input->post("password", true);

        // Gibt es bereits einen Benutzer mit dieser E-Mailadresse?
        if($this->Users_Model->get_user_by_id($username)) exit("false");
        else {
            $this->Users_Model->register($username, $password);
            exit("true");
        }
    }

    public function logout()
    {
        $this->modules->Session->destroy();
    }
}