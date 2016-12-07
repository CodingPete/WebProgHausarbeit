<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 29.11.2016
 * Time: 17:26
 */
class Tracks_Model
{
    private $model_fields = array(
        "track_id",
        "user_id",
        "duration",
        "starttime",
        "waypoints",
        "waypoints_enc",
        "privacy",
        "public_key"
    );

    /**
     * @param $user_id Liefert alle Tracks eines Users zurück
     * @return array Liefert alle Tracks des Users zurück. Leeres Array wenn der User keine Tracks besitzt.
     */
    public function get_tracks_on_user($user_id)
    {
        $track_keys = $this->Database->hGet("$user_id:details", "track_keys");

        $result = array();

        for($i = $track_keys; $i > 0; $i--) {
            $entry = $this->Database->hGetAll("$user_id:$i");
            if(!empty($entry)) $result[$entry['track_id']] = $entry;
        }
        return $result;
    }

    public function get_public_tracks() {
        $public_keys = $this->Database->hGet("public:details", "public_keys");

        $result = array();

        for($i = $public_keys; $i > 0; $i--) {
            $entry = $this->Database->hGetAll("public:$i");
            if(!empty($entry)) $result[] = $entry;
        }
        return $result;
    }

    /**
     * @param $data Rawdata des Tracks.
     * @return bool True wenn erfolgreich erstellt, false wenn Fehler.
     */
    public function create_track($data)
    {
        $track = $this->validate_input($data);

        if ($track) {
            $user_id = $track['user_id'];
            $track_id = $track['track_id'] = $this->Database->hIncrBy("$user_id:details", "track_keys", 1);
            $track["duration"] = $this->get_duration($track);
            $track["starttime"] = $track["waypoints"][0]->timestamp / 1000;
            $track['waypoints'] = json_encode($track['waypoints']);
            $this->Database->hMSet("$user_id:$track_id", $track);
            return true;
        }
        else return false;
    }

    /**
     * @param $track_id Der zu holende Track
     * @return array|bool Liefert den Track als Array aus. False wenn der Track leer ist.
     */
    public function get_track($user_id, $track_id)
    {
        $track = $this->Database->hGetAll("$user_id:$track_id");
        $track['waypoints'] = json_decode($track['waypoints']);

        if (!empty($track)) return $track;
        else return false;
    }

    /**
     * @param $data Rawdata des Tracks.
     * @return bool True wenn Track erfolgreich geupdatet. False wenn nicht.
     */
    public function update_track($data)
    {
        $track = $this->validate_input($data);

        if ($track) {
            $user_id = $track['user_id'];
            $track_id = $track['track_id'];
            $track["waypoints"] = json_encode($track["waypoints"]);
            return $this->Database->hMSet("$user_id:$track_id", $track);
        }
        else return false;

    }

    public function update_privacy($user_id, $track_id) {

        $track = $this->get_track($user_id, $track_id);

        // todo: Einträge werden doppelt erstellt
        // Falls nötig, Datensatz für geteilte Tracks initialisieren
        if(!$this->Database->hExists("public:details", "public_keys")) {
            $this->Database->hSet("public:details", "public_keys", 0);
        }

        // Bei änderung eines Tracks die Sichtbarkeit prüfen
        if($track["privacy"] == "private") {
            // Datenbankeintrag löschen
            $public_key = $track["public_key"];
            $this->Database->del("public:$public_key");
            $this->Database->hDel("$user_id:$track_id", "public_key");
        }
        else {

            // Ggf vorherige Verknüpfung unkenntlich machen
            if(isset($track["public_key"])) {
                $public_key = $track["public_key"];
                $this->Database->del("public:$public_key");
                $this->Database->hDel("$user_id:$track_id", "public_key");
            }
            // Dateneinbanktrag erstellen
            $public_key = $this->Database->hIncrBy("public:details", "public_keys", 1);
            $this->Database->hMSet("public:$public_key", array(
                "track_id" => $track_id,
                "user_id" => $user_id,
                "startpoint" => json_encode($track["waypoints"][0])
            ));
            $this->Database->hSet("$user_id:$track_id", "public_key", $public_key);
        }
    }

    /**
     * @param $track_id Der zu löschende Track
     * @param $user_id Der Besitzer des Tracks
     * @return integer Anzahl der gelöschten Schlüssel, 0 wenn Schlüssel nicht gefunden.
     */
    public function delete_track($user_id, $track_id)
    {
        $this->Database->del("$user_id:$track_id");
    }

    /**
     * @param $data Rawdata des Tracks
     * @return array|bool Liefert die übergebenen Rohdaten als array zurück, false wenn Rohdaten leer
     */
    public function validate_input($data)
    {
        foreach ($this->model_fields as $model_field)
            if(isset($data[$model_field]))
                $track[$model_field] = $data[$model_field];

        if (isset($track) && !empty($track) && !empty($track['user_id'])) return $track;
        else return false;
    }

    /**
     * @param $track Der Track für den die Dauer berechnet werden soll.
     * @return float|int Gibt die Dauer des Tracks in Minuten zurück.
     *
     */
    public function get_duration($track) {

        $duration = 0;


            $waypoints = $track["waypoints"];

            for ($i = 0; $i < count($waypoints); $i++) {
                $current = $waypoints[$i];
                if ($i < count($waypoints) - 1) {
                    $next = $waypoints[$i + 1];
                } else $next = null;

                if (!isset($current->timestamp))
                    continue;

                if (!is_null($next)) {
                    if (isset($next->is_start) && !$next->is_start) {
                        $duration = $duration + $next->timestamp - $current->timestamp;
                    } else continue;
                } else continue;
            }

            $duration = floor($duration / 1000 / 60);

        return $duration;
    }
}