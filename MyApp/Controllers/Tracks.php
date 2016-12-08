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

    public function view_public_track()
    {
        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $this->modules->Model->load("Tracks_Model");

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if ($track) {
            $this->modules->View->assign("tracklist", array(
                "tracklist" => array($track),
                "user_id" => $this->modules->Session->get("user_id")
            ));
            $this->modules->View->render();
        } else echo "Track nicht gefunden :(";

    }

    public function download_xml() {
        $this->modules->Model->load("Tracks_Model");

        $user_id = $this->modules->Input->get("user_id", true);
        $track_id = $this->modules->Input->get("track_id", true);

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if($track) {
            $gpx = new SimpleXMLElement("<gpx></gpx>");
            $gpx->addAttribute("version", "1.1");
            $gpx->addAttribute("creator", "MyTrack");

            for($i = 0; $i < count($track["waypoints"]); $i++) {
                $wpt = $gpx->addChild("wpt");
                $wpt->addAttribute("lat", $track["waypoints"][$i]->lat);
                $wpt->addAttribute("lon", $track["waypoints"][$i]->lng);
                if(isset($track["waypoints"][$i]->alt)) {
                    $ele = $wpt->addChild("ele", $track["waypoints"][$i]->alt);
                }
                else $ele = $wpt->addChild("ele", 0);
                if(isset($track["waypoints"][$i]->alt)) {
                    $time = $wpt->addChild("time", date("Y-d-mTG:i:sz", $track["waypoints"][$i]->timestamp / 1000));
                }
                else $time = $wpt->addChild("time", 0);
                $name = $wpt->addChild("name", $i);

            }
            header('Content-type: text/xml');
            header('Content-Disposition: attachment; filename="'.$user_id.'_'.$track_id.'.xml"');
            echo $gpx->asXML();
        }
    }

    public function upload_xml_html()
    {
        $this->modules->View->assign("xml_upload");
        $this->modules->View->render();
    }

    public function upload_xml()
    {
        $this->modules->Model->load("Tracks_Model");

        $filename = $_FILES['gpx_file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == "gpx" || $ext == "GPX") {

            $gpx_string = file_get_contents($_FILES["gpx_file"]["tmp_name"]);

            $xml = simplexml_load_string($gpx_string) or die("Ungültige Datei :(");

            $waypoints = array();

            foreach ($xml->wpt as $waypoint) {
                $waypoints[] = (object)array(
                    "lat" => (string)$waypoint->attributes()["lat"],
                    "lng" => (string)$waypoint->attributes()["lon"],
                    "alt" => (string)$waypoint->ele,
                    "timestamp" => strtotime((string)$waypoint->time),
                );
            }

            $track = array(
                "duration" => 0,
                "waypoints" => $waypoints,
                "user_id" => $this->modules->Session->get("user_id"),
                "privacy" => "private",
                "starttime" => $waypoints[0]->timestamp,
                "track_id" => "dont-care",
                "waypoints_enc" => $this->encode_path($waypoints)
            );

            if ($this->Tracks_Model->create_track($track)) exit("true");
            else exit("false");
        }
        exit("false");
    }

    // Auf Basis von https://developers.google.com/maps/documentation/utilities/polylinealgorithm?hl=de
    private function encode_path($waypoints)
    {
        $result = "";

        for ($i = 0; $i < count($waypoints); $i++) {

            $current = $waypoints[$i];
            if ($i > 0) $previous = $waypoints[$i - 1];
            else $previous = null;

            // Falls der erste Wegpunkt vorliegt, ...
            if (is_null($previous)) {
                // ... dann kann die erste Codierung ohne Offset berechnet werden
                $result .= $this->encode_coord_part($current->lat);
                $result .= $this->encode_coord_part($current->lng);
            }
            // ... ansonsten die Differenz vom vorherigen Wegpunkt zur codierung heranziehen
            else {
                $difference_lat = $current->lat - $previous->lat;
                $difference_lng = $current->lng - $previous->lng;

                $result .= $this->encode_coord_part($difference_lat);
                $result .= $this->encode_coord_part($difference_lng);
            }

        }
        return $result;
    }

    private function encode_coord_part($value)
    {

        $result = "";

        // Wert * 10 Hoch 5 rechnen
        $initial = $value = $value * pow(10, 5);

        // Ursprüngliche Fließkommazahl runden und dann zu Integer casten
        $value = (int)round($value);

        // Shift links
        $value <<= 1;

        // Wenn der Eingangswert kleiner null ist, dann den Wert invertieren.
        if ($initial < 0) $value = ~$value;

        // Solange noch mehr als 5 Bits vorhanden sind
        while ($value >= 32) {
            // Wert auf länge bringen, verodern, + 63 und dann zu Ascii
            $result .= chr((32 | ($value & 31)) + 63);
            // Zum nächsten 5 Bit Chunk shiften
            $value >>= 5;
        }

        // Letzten 5 Bit Chunk auch +63 und zu Ascii
        $result .= chr($value + 63);

        return $result;
    }

}