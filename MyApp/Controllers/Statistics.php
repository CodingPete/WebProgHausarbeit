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

    public function get_overall_stats()
    {

        echo "ERGEBNIS";
    }

    public function get_track_stats()
    {

        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $error = array();

        $this->modules->Model->load("Tracks_Model");

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        // Wenn der Track gültig ist
        if($track) {

            // Hole gesäuberte Wegpunkte
            $waypoints = $this->strip_waypoints($track["waypoints"]);

            // Wenn nach dem Filter noch mindestens 2 Wegpunkte vorhanden sind ...
            if(count($waypoints) > 1) {

                // ... nur dann macht es Sinn Statistiken zu erstellen.
                echo "Ich würde jetzt Statistiken erstellen wenn ich könnte...<br>";

            }
            else $error[] = "Es konnten keine Statistiken erstellt werden :(";
        }
        else $error[] = "Fehler im Track :(";

        // Gab es Fehler? Wenn ja ausgeben
        if(!empty($error)) {
            foreach($error as $err) echo $err;
        }

        if($this->modules->Session->get("user_id") == $user_id) {
            $delete_button = "<button class='btn-danger delete' user='$user_id' track='$track_id'>Track löschen</button>";
            echo $delete_button;
        }
    }

    /**
     * @param $waypoints Zu bearbeitende Wegpunkte
     * @return array Wegpunkte ohne Einträge mit fehlendem Timestamp
     */
    private function strip_waypoints($waypoints)
    {
        $result = array();
        foreach($waypoints as $waypoint) {
            if(isset($waypoint->timestamp)) {
                $result[] = $waypoint;
            }
        }
        return $result;
    }

    private function svg_altitude() {

    }
}