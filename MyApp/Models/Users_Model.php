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

    public function set_avatar($user_id, $avatar) {
        $this->Database->hMSet("$user_id:details", array("avatar" => $avatar));
    }

    public function get_avatar($user_id) {
        return $this->Database->hGet("$user_id:details", "avatar");
    }

    public function delete_user($user_id) {
        $this->Database->del($user_id);
    }

}