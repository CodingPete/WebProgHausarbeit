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
        "waypoints",
        "privacy"
    );

    /**
     * @param $user_id Liefert alle Tracks eines Users zurück
     * @return array Liefert alle Tracks des Users zurück. Leeres Array wenn der User keine Tracks besitzt.
     */
    public function get_tracks_on_user($user_id)
    {
        $track_keys = $this->Database->hGet("$user_id:details", "track_keys");

        $result = array();

        for($i = 1; $i <= $track_keys; $i++) {
            $entry = $this->Database->hGetAll("$user_id:$i");
            if(!empty($entry)) $result[$entry['track_id']] = $entry;
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
            return $this->Database->hMSet("$user_id:$track_id", $track);
        }
        else return false;

    }

    /**
     * @param $track_id Der zu löschende Track
     * @param $user_id Der Besitzer des Tracks
     * @return integer Anzahl der gelöschten Schlüssel, 0 wenn Schlüssel nicht gefunden.
     */
    public function delete_track($track_id, $user_id)
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
            $track[$model_field] = $data[$model_field];

        if (isset($track) && !empty($track)) return $track;
        else return false;
    }
}