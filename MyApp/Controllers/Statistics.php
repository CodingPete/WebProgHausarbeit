<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 06.12.2016
 * Time: 16:05
 */
class Statistics extends Framework
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

    public function get_overall_stats() {

        echo "ERGEBNIS";
    }

    public function get_track_stats() {

        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $delete_button = "<button class='btn btn-danger delete' user='$user_id' track='$track_id'>Löschen</button>";

        echo $delete_button;
    }

}