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
        // todo: Gesamtstatistiken anlegen.

        // Alle Tracks holen.
        $this->modules->Model->load("Tracks_Model");
        $user_id = $this->modules->Session->get("user_id");

        $tracks = $this->Tracks_Model->get_tracks_on_user($user_id);

        // Ergebnisvariablen
        $strecke = 0;
        $zeit = 0;
        $v_max = array();
        $track_count = count($tracks);

        foreach($tracks as $track) {
            if($track) {
                if(isset($track["waypoints"])) {
                    $waypoints = json_decode($track["waypoints"]);
                    $strecke += $this->info_distance($waypoints);
                    $v_max[] = $this->info_max_speed($waypoints);
                }
                if(isset($track["duration"]))
                    $zeit += $track["duration"];
            }
        }

        $v_max = max($v_max);

        $this->modules->View->assign("overall_stats", array(
            "track_count" => $track_count,
            "strecke" => $strecke,
            "zeit" => $zeit,
            "v_max" => $v_max
        ));

        $this->modules->View->render();

        // Wenn der eingeloggte Benutzer gleich dem Besitzer ist, Löschenbutton anbieten.
        if ($this->modules->Session->get("user_id") == $user_id) {
            $delete_button = "<button class='btn-danger delete_kto' user='$user_id'>Konto löschen</button>";
            echo $delete_button;
        }
    }

    // Liefert die Statisken eines Tracks
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
                    $text_stats = new DOMDocument();
                    $div = $text_stats->createElement("div");
                    $div->appendChild($text_stats->createTextNode($this->info_distance($waypoints) . " Kilometer"));
                    $div->appendChild($text_stats->createElement("br"));

                    $div->appendChild($text_stats->createTextNode($this->info_avg_speed($waypoints) . " km/h Durchschnittsgeschwindigkeit"));
                    $div->appendChild($text_stats->createElement("br"));

                    $div->appendChild($text_stats->createTextNode($this->info_max_speed($waypoints) . " km/h V-Max"));
                    $div->appendChild($text_stats->createElement("br"));

                    $div->appendChild($text_stats->createTextNode($this->info_altitude($waypoints) . " Höhenmeter überwunden"));
                    $div->appendChild($text_stats->createElement("br"));

                    $div->appendChild($text_stats->createTextNode($this->info_max_alt($waypoints) . " Höchster Punkt"));
                    $div->appendChild($text_stats->createElement("br"));
                    $div->setAttribute("style", "text-align: left;");
                    $text_stats->appendChild($div);
                    echo $text_stats->saveHTML();
                    echo $this->svg_speed($waypoints);
                    echo $this->svg_speed_average($waypoints);
                    echo $this->svg_altitude($waypoints);
                    echo $this->svg_altitude_kum($waypoints);
                } catch (Exception $e) {
                    $error[] = $e->getMessage();
                }
            } else $error[] = "Es konnten keine Statistiken erstellt werden :(";
        } else $error[] = "Fehler im Track :(";

        // Gab es Fehler? Wenn ja ausgeben
        if (!empty($error)) {
            echo "Es konnten keine Statistiken erstellt werden :(";
        }

        // Wenn der eingeloggte Benutzer gleich dem Besitzer ist, Löschenbutton anbieten.
        if ($this->modules->Session->get("user_id") == $user_id) {
            $delete_button = "<button class='btn-danger delete' user='$user_id' track='$track_id'>Track löschen</button>";
            echo $delete_button;
        }
    }

    // Erstellt das Geschwindigkeitsdiagramm
    private function svg_speed($waypoints)
    {
       foreach($waypoints as $waypoint) {

           if(isset($waypoint->speed))
                $speed[] = $waypoint->speed * 3.6;
           else $speed[] = 0;
        }

        // Aus den Ergebnissen nun einen Graphen erstellen

        // Breite des Diagramms
        $chart_width = 500;

        // Höhe des Diagramms
        $chart_height = 100;

        // Höchste erreichte Geschwindigkeit als Maximalauschlag des Graphen
        $max = max($speed);
        if($max <= 0) return "";

        // Anzahl Spalten
        $col_count = count($speed);

        // Spaltenbreite = Diagrammbreite Durch Anzahlspalten
        $col_width = $chart_width / $col_count;

        $scale_width = 5;



        // Svg Element erstellen und Skala bauen.
        $graph = $this->svg_init_and_scale($chart_width, $chart_height, $scale_width, $max);

        // Für die Anzahl Spalten
        for($i = 0; $i < $col_count; $i++) {

            // Startposition des Balken berechnen.
            $x = $col_width + $i * $col_width;
            $col_height = $speed[$i] / $max * 100;  // Balkenhöhe ist die Geschwindigkeit im Verhältnis zur maximalen Geschwindigkeit
            $y = 100 - $col_height;

            // Balkenelement mit den berechneten Werten erstellen.
            $rect = $graph->createElement("rect");
            $rect->setAttribute("x", $x);
            $rect->setAttribute("y", $y);
            $rect->setAttribute("width", $col_width);
            $rect->setAttribute("height", $col_height);    // berechnen mit Datavalue
            $rect->setAttribute("fill", "red");
            $graph->getElementsByTagName("svg")->item(0)->appendChild($rect);
        }


        return $graph->saveHTML();
    }

    // Erstellt das Durchschnittsgeschwindigkeitsdiagramm
    private function svg_speed_average($waypoints)
    {
        foreach($waypoints as $waypoint) {

            if(isset($waypoint->speed))
                $speed[] = $waypoint->speed * 3.6;
            else $speed[] = 0;
        }

        // Aus den Ergebnissen nun einen Graphen erstellen

        // Breite des Diagramms
        $chart_width = 500;

        // Höhe des Diagramms
        $chart_height = 100;

        // Höchste erreichte Geschwindigkeit als Maximalauschlag des Graphen
        $max = max($speed);
        if($max <= 0) return "";

        // Anzahl Spalten
        $col_count = count($speed);

        // Spaltenbreite = Diagrammbreite Durch Anzahlspalten
        $col_width = $chart_width / $col_count;

        $scale_width = 5;



        // Svg Element erstellen und Skala bauen.
        $graph = $this->svg_init_and_scale($chart_width, $chart_height, $scale_width, $max);

        // Für die Anzahl Spalten
        for($i = 0; $i < $col_count; $i++) {

            // Startposition des Balken berechnen.
            $x = $col_width + $i * $col_width;
            $col_height = $this->average($speed, $i);  // Balkenhöhe ist die Geschwindigkeit im Verhältnis zur maximalen Geschwindigkeit
            $y = 100 - $col_height;

            // Balkenelement mit den berechneten Werten erstellen.
            $rect = $graph->createElement("rect");
            $rect->setAttribute("x", $x);
            $rect->setAttribute("y", $y);
            $rect->setAttribute("width", $col_width);
            $rect->setAttribute("height", $col_height);    // berechnen mit Datavalue
            $rect->setAttribute("fill", "red");
            $graph->getElementsByTagName("svg")->item(0)->appendChild($rect);
        }


        return $graph->saveHTML();
    }

    // Erstellt das Höhendiagramm
    private function svg_altitude($waypoints) {
        foreach($waypoints as $waypoint) {

            if(isset($waypoint->alt))
                $alt[] = $waypoint->alt;
            else $alt[] = 0;
        }

        // Aus den Ergebnissen nun einen Graphen erstellen

        // Breite des Diagramms
        $chart_width = 500;

        // Höhe des Diagramms
        $chart_height = 100;

        // Höchste erreichte Höhe als Maximalauschlag des Graphen
        $max = max($alt);
        if($max <= 0) return "";

        // Anzahl Spalten
        $col_count = count($alt);

        // Spaltenbreite = Diagrammbreite Durch Anzahlspalten
        $col_width = $chart_width / $col_count;

        $scale_width = 30;



        // Svg Element erstellen und Skala bauen.
        $graph = $this->svg_init_and_scale($chart_width, $chart_height, $scale_width, $max);

        // Für die Anzahl Spalten
        for($i = 0; $i < $col_count; $i++) {

            // Startposition des Balken berechnen.
            $x = $scale_width + $i * $col_width;
            $col_height = $alt[$i] / $max * 100;  // Balkenhöhe ist die Geschwindigkeit im Verhältnis zur maximalen Geschwindigkeit
            $y = 100 - $col_height;

            // Balkenelement mit den berechneten Werten erstellen.
            $rect = $graph->createElement("rect");
            $rect->setAttribute("x", $x);
            $rect->setAttribute("y", $y);
            $rect->setAttribute("width", $col_width);
            $rect->setAttribute("height", $col_height);    // berechnen mit Datavalue
            $rect->setAttribute("fill", "red");
            $graph->getElementsByTagName("svg")->item(0)->appendChild($rect);
        }


        return $graph->saveHTML();
    }

    // Erstellt das kummulierte Höhendiagramm
    private function svg_altitude_kum($waypoints) {

        foreach($waypoints as $waypoint) {

            if(isset($waypoint->alt))
                $alt[] = $waypoint->alt;
            else $alt[] = 0;
        }

        // Kummuliere
        for($i = 1; $i < count($alt); $i++) {
            $alt[$i] = $alt[$i] - $alt[$i - 1];
        }

        // Aus den Ergebnissen nun einen Graphen erstellen

        // Breite des Diagramms
        $chart_width = 500;

        // Höhe des Diagramms
        $chart_height = 100;

        // Höchste erreichte Höhe als Maximalauschlag des Graphen
        $max = max($alt);
        if($max <= 0) return "";

        // Anzahl Spalten
        $col_count = count($alt);

        // Spaltenbreite = Diagrammbreite Durch Anzahlspalten
        $col_width = $chart_width / $col_count;

        $scale_width = 30;



        // Svg Element erstellen und Skala bauen.
        $graph = $this->svg_init_and_scale($chart_width, $chart_height, $scale_width, $max);

        // Für die Anzahl Spalten
        for($i = 0; $i < $col_count; $i++) {

            // Startposition des Balken berechnen.
            $x = $scale_width + $i * $col_width;
            $col_height = $alt[$i] / $max * 100;  // Balkenhöhe ist die Geschwindigkeit im Verhältnis zur maximalen Geschwindigkeit
            $y = 100 - $col_height;

            // Balkenelement mit den berechneten Werten erstellen.
            $rect = $graph->createElement("rect");
            $rect->setAttribute("x", $x);
            $rect->setAttribute("y", $y);
            $rect->setAttribute("width", $col_width);
            $rect->setAttribute("height", $col_height);    // berechnen mit Datavalue
            $rect->setAttribute("fill", "red");
            $graph->getElementsByTagName("svg")->item(0)->appendChild($rect);
        }


        return $graph->saveHTML();
    }

    // Erstellt ein Chart Grundgerüst mit Skala
    private function svg_init_and_scale($chart_width, $chart_height, $scale_width, $max) {
        // Svg Element erstellen.
        $graph = new DomDocument();
        $svg = $graph->createElement("svg");
        $svg->setAttribute("class", "chart");
        $svg->setAttribute("viewBox", "0 0 $chart_width $chart_height");
        $graph->appendChild($svg);


        // Zeit für eine Skala
        $scale = $graph->createElement("line");
        $scale->setAttribute("x1", 0);
        $scale->setAttribute("y1", 5);
        $scale->setAttribute("x2", 0);
        $scale->setAttribute("y2", $chart_height);
        $scale->setAttribute("stroke", "white");
        $svg->appendChild($scale);

        // Oberer Strich
        $top_mark = $graph->createElement("line");
        $top_mark->setAttribute("x1", 0);
        $top_mark->setAttribute("y1", 5);
        $top_mark->setAttribute("x2", 5);
        $top_mark->setAttribute("y2", 5);
        $top_mark->setAttribute("stroke", "white");
        $svg->appendChild($top_mark);

        // Mittlerer Strich
        $center_mark = $graph->createElement("line");
        $center_mark->setAttribute("x1", 0);
        $center_mark->setAttribute("y1", $chart_height / 2);
        $center_mark->setAttribute("x2", 5);
        $center_mark->setAttribute("y2", $chart_height / 2);
        $center_mark->setAttribute("stroke", "white");
        $svg->appendChild($center_mark);

        // Unterer Strich
        $bottom_mark = $graph->createElement("line");
        $bottom_mark->setAttribute("x1", 0);
        $bottom_mark->setAttribute("y1", $chart_height);
        $bottom_mark->setAttribute("x2", 5);
        $bottom_mark->setAttribute("y2", $chart_height);
        $bottom_mark->setAttribute("stroke", "white");
        $svg->appendChild($bottom_mark);

        // Oberer Text
        $top_label = $graph->createElement("text");
        $top_label->setAttribute("x", 3);
        $top_label->setAttribute("y", 15);
        $top_label->setAttribute("fill", "white");
        $top_label->setAttribute("font-size", "0.5em");
        $top_label->appendChild($graph->createTextNode(number_format($max, 2)));
        $svg->appendChild($top_label);

        // Mittlerer Text
        $center_label = $graph->createElement("text");
        $center_label->setAttribute("x", 3);
        $center_label->setAttribute("y", $chart_height / 2 + 15);
        $center_label->setAttribute("fill", "white");
        $center_label->setAttribute("font-size", "0.5em");
        $center_label->appendChild($graph->createTextNode(number_format($max / 2, 2)));
        $svg->appendChild($center_label);

        return $graph;
    }


    //Berechnet die Länge eines Track
    private function info_distance($waypoints) {

        $distance = 0;

        for($i = 1; $i < count($waypoints); $i++) {
            $distance += $this->distance_between_points($waypoints[$i - 1], $waypoints[$i]);
        }

        return number_format($distance, 2);
    }

    // Berechnet die kumulierte Höhe
    private function info_altitude($waypoints) {
        $altitude = 0;

        for($i = 1; $i < count($waypoints); $i++) {
            $altitude += $waypoints[$i]->alt - $waypoints[$i - 1]->alt;
        }

        return number_format($altitude, 2);
    }

    // Berechnet die höchste erreichte Geschwindigkeit
    private function info_max_speed($waypoints) {
        foreach($waypoints as $waypoint) {

            if(isset($waypoint->speed))
                $speed[] = $waypoint->speed * 3.6;
            else $speed[] = 0;
        }

        return number_format(max($speed), 2);
    }

    // Berechnet die Durchschnittsgeschwindigkeit
    private function info_avg_speed($waypoints) {
        $speed = array();

        foreach($waypoints as $waypoint) {
            if(isset($waypoint->speed)) $speed[] = $waypoint->speed;
            else $speed[] = 0;
        }
        return number_format($this->average($speed, count($speed)), 2);
    }

    // Berechnet die höchste Höhe
    private function info_max_alt($waypoints) {
        foreach($waypoints as $waypoint) {

            if(isset($waypoint->alt))
                $alt[] = $waypoint->alt;
            else $alt[] = 0;
        }

        return number_format(max($alt), 2);
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

    private function average($values, $i) {
        $average = 0;

        // Den Durchschnitt von Werten bis zum index i berechnen
        for($k = 0; $k != $i; $k++) {
            $average += $values[$k];
        }

        // Wenn weiter als das erste Element im Array gezählt werden soll
        if($k > 0) return $average / $k;

        // Ansonsten einfach das erste Element zurückgeben.
        else return $values[0];
    }

    // Kickt unbrauchbare Wegpunkte heraus.
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
}