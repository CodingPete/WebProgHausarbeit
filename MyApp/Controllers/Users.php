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

        // POST-Parameter holen.
        $username = $this->modules->Input->post("username", true);
        $password = $this->modules->Input->post("password", true);

        // Wenn der Login gültig ist.
        if($this->Users_Model->login($username, $password)) {

            // Sessionvariabln setzen
            $this->modules->Session->set("user_type", "user");
            $this->modules->Session->set("user_id", $username);
            exit("true");
        }
        else exit("false");
    }

    public function register() {

        $this->modules->Model->load("Users_Model");

        // POST-Parameter holen.
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

    public function ajax_upload_avatar_html() {
        $this->modules->View->assign("avatar");
        $this->modules->View->render();
    }

    public function ajax_upload_avatar() {
        $this->modules->Model->load("Users_Model");

        $user_id = $this->modules->Session->get("user_id");
        $avatar = $this->modules->Input->post("avatar", false);

        $this->Users_Model->set_avatar($user_id, $avatar);
    }

    public function ajax_get_avatar() {
        $this->modules->Model->load("Users_Model");
        $user_id = $this->modules->Session->get("user_id");

        $avatar = $this->Users_Model->get_avatar($user_id);
        if(!$avatar) exit("false");
        exit($avatar);
    }

    public function ajax_delete_user() {
        $user_id = $this->modules->Input->post("user_id", true);

        $this->modules->Model->load("Tracks_Model");
        $this->modules->Model->load("Users_Model");

        // Alle Tracks des Users holen.
        $tracks = $this->Tracks_Model->get_tracks_on_user($user_id);

        // Nur wenn der Benutzer eigentümer ist
        if($user_id == $this->modules->Session->get("user_id")) {

            // Alle Tracks des Users löschen
            foreach ($tracks as $track) {
                $this->Tracks_Model->delete_track($user_id, $track["track_id"]);
            }

            // User löschen
            $this->Users_Model->delete_user($user_id);
        }

    }
}