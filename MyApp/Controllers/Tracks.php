<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 29.11.2016
 * Time: 17:24
 */
class Tracks extends Framework
{

    public function __construct()
    {
        parent::__construct();
        if ($this->modules->Session->get("user_type") != "user") $this->index();
    }

    public function index()
    {
        header("Location: " . APP_DOMAIN);
    }

    public function ajax_get_tracks_on_user() {
        $this->modules->Model->load("Tracks_Model");

        $user_id = $this->modules->Input->post("user_id", true);

        $tracks_on_user = $this->Tracks_Model->get_tracks_on_user($user_id);

        return $tracks_on_user;
    }

    public function ajax_create_track() {
        $this->modules->Model->load("Tracks_Model");

        $input_track = json_decode($this->modules->Input->post("data", true));

        $return = $this->Tracks_Model->create_track($input_track);

        exit($return);
    }



















    public function test_get_tracks_on_user()
    {
        $test_user_id = "peter.meyer.fl@googlemail.com";

        $this->modules->Model->load("Tracks_Model");

        echo "<pre>";
        print_r($this->Tracks_Model->get_tracks_on_user($test_user_id));
        echo "</pre>";
    }

    public function test_create_track()
    {

        $test_track = array(
            "track_id" => "dont-care",
            "user_id" => "peter.meyer.fl@googlemail.com",
            "waypoints" => "dont-care",
            "privacy" => "public"
        );


        $this->modules->Model->load("Tracks_Model");

        echo $this->Tracks_Model->create_track($test_track);
    }

    public function test_get_track()
    {

        $test_user_id = "peter.meyer.fl@googlemail.com";
        $test_track_id = 5;

        $this->modules->Model->load("Tracks_Model");

        echo "<pre>";
        print_r($this->Tracks_Model->get_track($test_user_id, $test_track_id));
        echo "</pre>";
    }

    public function test_update_track()
    {
        $test_track = array(
            "track_id" => "5",
            "user_id" => "peter.meyer.fl@googlemail.com",
            "waypoints" => "4xRechts nach Panama",
            "privacy" => "private"
        );

        $this->modules->Model->load("Tracks_Model");

        echo "<pre>";
        print_r($this->Tracks_Model->update_track($test_track));
        echo "</pre>";
    }
}