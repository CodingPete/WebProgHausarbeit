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

        // Wenn kein Nutzer in der Session vorliegt, dann soll der weg.
        if ($this->modules->Session->get("user_type") != "user") $this->index();
    }

    public function index()
    {
        // Nutzer umleiten auf Hauptseite.
        header("Location: " . APP_DOMAIN);
    }


    // Gibt die Trackliste aus
    public function ajax_get_track_list_html()
    {
        // Holt eine Liste der Tracks und die User_ID und schreibt diese in die View.
        $this->modules->View->assign("tracklist", array(
            "tracklist" => $this->get_tracks_on_user(),
            "user_id" => $this->modules->Session->get("user_id")
        ));
        $this->modules->View->render();
    }

    // Holt einen Track aus der Datenbank
    public function ajax_get_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $user_id = $this->modules->Input->post("user_id", true);
        $track_id = $this->modules->Input->post("track_id", true);

        $track = $this->Tracks_Model->get_track($user_id, $track_id);
        exit(json_encode($track));
    }

    // Liefert alle Tracks eines Users.
    private function get_tracks_on_user()
    {
        $this->modules->Model->load("Tracks_Model");

        $user_id = $this->modules->Session->get("user_id");

        $tracks_on_user = $this->Tracks_Model->get_tracks_on_user($user_id);

        return $tracks_on_user;
    }

    // Erstellt einen Track in der Datenbank
    public function ajax_create_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $input_track = (array)json_decode($this->modules->Input->post("payload", false));

        if ($this->Tracks_Model->create_track($input_track)) exit("true");
        else exit("false");
    }

    // Löscht einen Track aus der Datenbank
    public function ajax_delete_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $track_id = $this->modules->Input->post("track_id", true);
        $user_id = $this->modules->Input->post("user_id", true);

        $this->Tracks_Model->delete_track($user_id, $track_id);

    }

    // Ändert einen Track in der Datenbank
    public function ajax_update_track()
    {
        $this->modules->Model->load("Tracks_Model");

        $track_id = $this->modules->Input->post("track_id", true);
        $user_id = $this->modules->Input->post("user_id", true);

        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if ($track) {

            // Alle Werte die im Track vorliegen.
            $keys = array_keys($track);

            foreach ($keys as $key) {

                // Jeden Key aus den Post-Parametern holen.
                $field_to_change = $this->modules->Input->post($key, false);
                // Wenn es einen gültigen Post-Parameter gibt.
                if ($field_to_change) {
                    // Dann den Track an dieser Stelle updaten.
                    $track[$key] = $field_to_change;
                }
            }

            // Änderungen in die Datenbank schreiben.
            if ($this->Tracks_Model->update_track($track)) {

                // Falls die Sichtbarkeit geändert worden ist, muss ein weiteres Schlüsselpaar angelegt/gelöscht werden
                $this->Tracks_Model->update_privacy($user_id, $track_id);

                return "true";
            } else return "false";
        }
        return "false";
    }

    // Holt alle freigegebenen Tracks die innerhalb des Kartenausschnittes liegen.
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
                // Liegt die Startposition des Tracks innerhalb der Latitude oben links und unten rechts der karte?
                if ($startpoint->lat >= $bounds["sw"]["lat"] && $startpoint->lat <= $bounds["ne"]["lat"]) {
                    // Liegt die Startposition des Tracks innerhalb der Longitude oben links und unten rechts der karte?
                    if ($startpoint->lng >= $bounds["sw"]["lng"] && $startpoint->lng <= $bounds["ne"]["lng"]) {
                        // Dann Startpoint setzen
                        $track["startpoint"] = $startpoint;
                        // und an das Array der beim Client anzuzeigenden Tracks kleben.
                        $result[] = $track;
                    }
                }
            }
        }
        exit(json_encode($result));

    }

    // Holt einen Public-Track und rendert die Trackliste für diesn
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

    // Download einer GPX Datei.
    public function download_xml() {
        $this->modules->Model->load("Tracks_Model");

        // Infos holen.
        $user_id = $this->modules->Input->get("user_id", true);
        $track_id = $this->modules->Input->get("track_id", true);

        // Track holen.
        $track = $this->Tracks_Model->get_track($user_id, $track_id);

        if($track) {

            // Leeres DOM-Dokument anlegen.
            $document = new DOMDocument();
            $document->formatOutput = true;

            // GPX-Tag schreiben.
            $gpx = $document->createElement("gpx");
            $gpx->setAttribute("version", "1.1");
            $gpx->setAttribute("creator", "MyTrack");
            $document->appendChild($gpx);

            // Für jeden Wegpunkt ....
            for($i = 0; $i < count($track["waypoints"]); $i++) {

                // ... wpt-Tag erstellen und LAT, LNG ablegen.
                $wpt = $document->createElement("wpt");
                $wpt->setAttribute("lat", $track["waypoints"][$i]->lat);
                $wpt->setAttribute("lon", $track["waypoints"][$i]->lng);

                // ... ele-Tag erstellen (Altitude)
                $ele = $document->createElement("ele");
                // Wenn ein Altitude-Wert beim Wegpunkt vorliegt.
                if(isset($track["waypoints"][$i]->alt))
                    $ele->appendChild($document->createTextNode($track["waypoints"][$i]->alt)); // Altidude
                else
                    $ele->appendChild($document->createTextNode("0")); // Ansonsten 0
                $wpt->appendChild($ele);


                // Wenn ein Timestamp-Wert beim Wegpunkt vorliegt, ...
                if(isset($track["waypoints"][$i]->timestamp)) {
                    $time = $document->createElement("time");
                    // ... diesen Anhängen. Zeitstempel / 1000 und dann nach ISO Date konvertieren.
                    $time->appendChild($document->createTextNode(date("c", $track["waypoints"][$i]->timestamp / 1000)));
                    $wpt->appendChild($time);
                }

                // Speed-Tag erstellen.
                $speed = $document->createElement("speed");
                if(isset($track["waypoints"][$i]->speed))
                    $speed->appendChild($document->createTextNode($track["waypoints"][$i]->speed));
                else
                    $speed->appendChild($document->createTextNode("0"));
                $wpt->appendChild($speed);

                $name = $document->createElement("name");
                $name->appendChild($document->createTextNode($i));
                $wpt->appendChild($name);
                $gpx->appendChild($wpt);

            }

            // HTTP-Header setzen und DOMDocument-Inhalt als XML ausgeben.
            header('Content-type: text/xml');
            header('Content-Disposition: attachment; filename="'.$user_id.'_'.$track_id.'.gpx"');
            echo $document->saveXML();
        }
    }

    // Rendert das Upload-Formular für GPX
    public function upload_xml_html()
    {
        $this->modules->View->assign("xml_upload");
        $this->modules->View->render();
    }

    // Empfängt die hochgeladene GPX Datei
    public function upload_xml()
    {
        $this->modules->Model->load("Tracks_Model");

        // Dateiinfos aus den globalen Variablen holen.
        $filename = $_FILES['gpx_file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Wenn die richtige Extension vorliegt.
        if ($ext == "gpx" || $ext == "GPX") {

            // String einlesen.
            $gpx_string = file_get_contents($_FILES["gpx_file"]["tmp_name"]);

            // Leeres DOMDocument anlegen.
            $xml = new DOMDocument();

            // Stringinhalt in das Dokument laden.
            $xml->loadXML($gpx_string);

            $waypoints = array();


            // Für jeden Wegpunkt im XML
            foreach ($xml->getElementsByTagName("wpt") as $waypoint) {

                // Ein Wegpunktobject mit den Daten anlegen.
                $waypoints[] = (object)array(
                    "lat" => (string)$waypoint->getAttribute("lat"),
                    "lng" => (string)$waypoint->getAttribute("lon"),
                    "alt" => (string)$waypoint->getElementsByTagName("ele")->item(0)->nodeValue,
                    "timestamp" => date("U", strtotime((string)$waypoint->getElementsByTagName("time")->item(0)->nodeValue)) * 1000,
                    "speed" => (string)$waypoint->getElementsByTagName("speed")->item(0)->nodeValue
                    );
            }


            // Einen neuen Track erstellen.
            $track = array(
                "duration" => 0,
                "waypoints" => $waypoints,
                "user_id" => $this->modules->Session->get("user_id"),
                "privacy" => "private",
                "starttime" => $waypoints[0]->timestamp,
                "track_id" => "dont-care",
                "waypoints_enc" => $this->encode_path($waypoints)
            );

            // Track in die Datenbank schreiben.
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

        // Es können doofe Zeichen errechnet werden, die Escaped werden müssen.
        return addslashes($result);
    }

}