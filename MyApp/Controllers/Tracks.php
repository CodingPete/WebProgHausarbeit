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


    public function ajax_get_track_list_html()
    {
        $this->modules->View->assign("tracklist", array(
            "tracklist" => $this->get_tracks_on_user(),
            "user_id" => $this->modules->Session->get("user_id")
        ));
        $this->modules->View->render();
    }

    public function ajax_get_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $track = $this->Tracks_Model->get_track($user_id, $track_id);
        exit(json_encode($track));
    }

    private function get_tracks_on_user()
    {
        $this->modules->Model->load("Tracks_Model");

        //$user_id = $this->modules->Input->post("user_id", true);
        $user_id = $this->modules->Session->get("user_id");

        $tracks_on_user = $this->Tracks_Model->get_tracks_on_user($user_id);

        return $tracks_on_user;
    }


    public function ajax_create_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $input_track = (array)json_decode($this->modules->Input->post("payload", false));

        if ($this->Tracks_Model->create_track($input_track)) exit("true");
        else exit("false");
    }

    public function ajax_delete_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $track_id = $this->modules->Input->post("track_id", true);
        $user_id = $this->modules->Input->post("user_id", true);

        $this->Tracks_Model->delete_track($user_id, $track_id);

    }

    public function ajax_update_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $track_id = $this->modules->Input->post("track_id", true);
        $user_id = $this->modules->Input->post("user_id", true);

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if ($track) {
            $keys = array_keys($track);

            foreach ($keys as $key) {
                $field_to_change = $this->modules->Input->post($key, false);
                if ($field_to_change) {
                    $track[$key] = $field_to_change;
                }
            }


            if ($this->Tracks_Model->update_track($track)) {

                // Falls die Sichtbarkeit geändert worden ist, muss ein weiteres Schlüsselpaar angelegt/gelöscht werden
                $this->Tracks_Model->update_privacy($user_id, $track_id);

                return "true";
            } else return "false";
        }
        return "false";
    }

    public function ajax_get_public_tracks_in_bounds()
    {

        $this->modules->Model->load("Tracks_Model");

        // Die übergebenen Ecken der Karte holen
        $bounds = $this->modules->Input->post("bounds", false);

        // Alle Einträge aus der Public-Tracks Tabelle holen. (Enthält nur die nötigen Schlüsselpaare und eine Startposition)
        $tracklist = $this->Tracks_Model->get_public_tracks();

        $result = array();

        // Für jeden Track prüfen ob seine Startkoordinate zu der Kartenansicht passt
        foreach ($tracklist as $track) {

            $startpoint = json_decode($track["startpoint"]);

            if (is_object($startpoint)) {
                if ($startpoint->lat >= $bounds["sw"]["lat"] && $startpoint->lat <= $bounds["ne"]["lat"]) {
                    if ($startpoint->lng >= $bounds["sw"]["lng"] && $startpoint->lng <= $bounds["ne"]["lng"]) {
                        $track["startpoint"] = $startpoint;
                        $result[] = $track;
                    }
                }
            }
        }
        exit(json_encode($result));

    }

    public function view_public_track() {
        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $this->modules->Model->load("Tracks_Model");

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if($track) {
            $this->modules->View->assign("tracklist", array(
                "tracklist" => array($track),
                "user_id" => $this->modules->Session->get("user_id")
            ));
            $this->modules->View->render();
        }
        else echo "Track nicht gefunden :(";

    }


    public
    function test_get_tracks_on_user()
    {
        $test_user_id = "peter.meyer.fl@googlemail.com";

        $this->modules->Model->load("Tracks_Model");

        echo "<pre>";
        print_r($this->Tracks_Model->get_tracks_on_user($test_user_id));
        echo "</pre>";
    }

    public
    function test_create_track()
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

    public
    function test_get_track()
    {

        $test_user_id = "peter.meyer.fl@googlemail.com";
        $test_track_id = 5;

        $this->modules->Model->load("Tracks_Model");

        echo "<pre>";
        print_r($this->Tracks_Model->get_track($test_user_id, $test_track_id));
        echo "</pre>";
    }

    public
    function test_update_track()
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