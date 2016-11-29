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

    public function get_user_by_email($email)
    {

        $user = $this->Database->hGetAll("$email:details");
        if (count($user) > 0) return $user;
        else return false;
    }

    public function register($email, $username, $password)
    {
        $this->Database->hMSet("$email:details", array(
            "email" => $email,
            "username" => $username,
            //"password" => md5($password)
            "password" => $password
        ));
    }

    public function login($email, $password)
    {
        $user = $this->get_user_by_email($email);
        if (count($user) > 0) {
            //if (($user["password"]) == md5($password)) return true;
            if($user['password'] == $password) return true;
        }
        return false;
    }


}