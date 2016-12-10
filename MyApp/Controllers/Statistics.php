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
        if ($track) {

            // Hole gesäuberte Wegpunkte
            $waypoints = $this->strip_waypoints($track["waypoints"]);

            // Wenn nach dem Filter noch mindestens 2 Wegpunkte vorhanden sind ...
            if (count($waypoints) > 1) {
                try {
                    // ... nur dann macht es Sinn Statistiken zu erstellen.
                    $geschwindigkeit = $this->geschwindigkeits_verlauf($waypoints);
                    echo $geschwindigkeit;
                } catch (Exception $e) {
                    $error[] = $e->getMessage();
                }
            } else $error[] = "Es konnten keine Statistiken erstellt werden :(";
        } else $error[] = "Fehler im Track :(";

        // Gab es Fehler? Wenn ja ausgeben
        if (!empty($error)) {
            echo "Es konnten keine Statistiken erstellt werden :(";
        }

        if ($this->modules->Session->get("user_id") == $user_id) {
            $delete_button = "<button class='btn-danger delete' user='$user_id' track='$track_id'>Track löschen</button>";
            echo $delete_button;
        }
    }

    private function geschwindigkeits_verlauf($waypoints)
    {
       foreach($waypoints as $waypoint) {

           if(isset($waypoint->speed))
                $speed[] = $waypoint->speed * 3.6;
           else $speed[] = 0;
        }

        // Aus den Ergebnissen nun einen Graphen erstellen

        // Breite des Diagramms
        $chart_width = 500;

        // Höchste erreichte Geschwindigkeit als Maximalauschlag des Graphen
        $max = max($speed);

        // Anzahl Spalten
        $col_count = count($speed);

        // Spaltenbreite = Diagrammbreite Durch Anzahlspalten
        $col_width = $chart_width / $col_count;



        // Svg Element erstellen.
        $graph = new DomDocument();
        $svg = $graph->createElement("svg");
        $svg->setAttribute("class", "chart");
        $svg->setAttribute("viewBox", "0 0 $chart_width 100");
        $graph->appendChild($svg);

        // Für die Anzahl Spalten
        for($i = 0; $i < $col_count; $i++) {

            // Startposition des Balken berechnen.
            $x = $i * $col_width;
            $col_height = $speed[$i] / $max * 100;  // Balkenhöhe ist die Geschwindigkeit im Verhältnis zur maximalen Geschwindigkeit
            $y = 100 - $col_height;

            // Balkenelement mit den berechneten Werten erstellen.
            $rect = $graph->createElement("rect");
            $rect->setAttribute("x", $x);
            $rect->setAttribute("y", $y);
            $rect->setAttribute("width", $col_width);
            $rect->setAttribute("height", $col_height);    // berechnen mit Datavalue
            $rect->setAttribute("fill", "red");
            $svg->appendChild($rect);
        }


        echo $graph->saveHTML();
    }

    // Anhand von http://www.kompf.de/gps/distcalc.html
    private function distance_between_points($wp_1, $wp_2)
    {
        $lat_1 = (float)$wp_1->lat;
        $lng_1 = (float)$wp_1->lng;

        $lat_2 = (float)$wp_2->lat;
        $lng_2 = (float)$wp_2->lng;

        $lat_between = ($lat_1 + $lat_2) / 2 * 0.01745;
        $lat_between = deg2rad($lat_between);

        $dx = 111.3 * cos($lat_between) * ($lng_1 - $lng_2);
        $dy = 111.3 * ($lat_1 - $lat_2);

        $distance = sqrt($dx * $dx + $dy * $dy);

        return $distance;
    }

    private function time_between_points($wp_1, $wp_2)
    {

        $time = $wp_2->timestamp - $wp_1->timestamp;

        // Zeit in Stunden zurückliefern
        return ($time / 60 / 60);
    }

    /**
     * @param $waypoints Zu bearbeitende Wegpunkte
     * @return array Wegpunkte ohne Einträge mit fehlendem Timestamp
     */
    private function strip_waypoints($waypoints)
    {
        $result = array();
        foreach ($waypoints as $waypoint) {
            if (isset($waypoint->timestamp)) {
                $result[] = $waypoint;
            }
        }
        return $result;
    }

    private function svg_altitude()
    {

    }
}