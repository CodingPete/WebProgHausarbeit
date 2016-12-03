<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 20:11
 */
class Users_Model
{
    public function __construct()
    {

    }

    public function get_user_by_id($user_id)
    {

        $user = $this->Database->hGetAll("$user_id:details");
        if (count($user) > 0) return $user;
        else return false;
    }

    public function register($username, $password)
    {
        $this->Database->hMSet("$username:details", array(
            "username" => $username,
            "password" => $password
        ));
    }

    public function login($username, $password)
    {
        $user = $this->get_user_by_id($username);
        if (count($user) > 0) {
            if($user['password'] == $password) return true;
        }
        return false;
    }


}