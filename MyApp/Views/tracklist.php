<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 12:54
 */
$static_maps_url = "https://maps.googleapis.com/maps/api/staticmap?size=200x100&path=weight:3%7Ccolor:0xff0000ff%7Cenc:";
$static_maps_key = "&key=AIzaSyBep0qQqNBiTtiXlvguRKrWj-UXIBQySEM";
?>
<style>
    select {
        color: #000000;
    }
    td {
        vertical-align: middle !important;
    }
</style>
<table class="table">
    <thead>
    <tr>
        <th></th>
        <th>Teilen?</th>
        <th>Dauer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tracklist as $track): ?>
        <tr>
            <td><img class="track_img" src="<?= $static_maps_url . $track["waypoints_enc"] . $static_maps_key; ?>" user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>"></td>
            <td>
                <select user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>">
                    <option value="<?= $track["privacy"]; ?>">
                        <?php
                        if($track["privacy"] == "private") echo "Nein";
                        else echo "Ja";
                        ?>
                    </option>
                    <option value="<?php
                        if ($track["privacy"] == "private") echo "public";
                        else echo "private"
                        ?>">
                        <?php
                        if($track["privacy"] == "private") echo "Ja";
                        else echo "Nein";
                        ?>
                    </option>
                </select>
            </td>
            <td>
                <?php
                $waypoints = json_decode($track["waypoints"]);
                $first = $waypoints[0]->timestamp;
                $last = array_pop($waypoints)->timestamp;
                $duration = floor(($last - $first) / 1000 / 60);
                echo $duration . " Minuten";
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
    $("select").on("change", function () {
        var track_id = $(this).attr("track");
        var user_id = $(this).attr("user");

        $.ajax({
            type: "POST",
            url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_update_track",
            data: {
                track_id: track_id,
                user_id: user_id,
                privacy: this.value
            },
            success: function (response) {
                if (response == "false") alert("Änderung fehlgeschlagen :(");
            }
        });
    });

    $(".track_img").on("click", function() {

        set_centering(false);

        // Laden des ausgewählten Tracks.
        var track_id = $(this).attr("track");
        var user_id = $(this).attr("user");
        track_id_viewd = track_id;
        $.ajax({
            type: "POST",
            url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_get_track",
            dataType: "json",
            data: {
                user_id: user_id,
                track_id: track_id
            },
            success: function(response) {
                var waypoints = response.waypoints;

                // Bisherigen Pfad löschen
                track_viewed.getPath().clear();
                // Wegpunkte in die Karte zeichnen
                track_viewed.setPath(waypoints);
                // Wegpunkte Array ebenfalls hinterlegen
                track_viewed_waypoints = waypoints;
                track_id_viewed = track_id;
                // Karte auf Anfangspunkt des Tracks zentrieren.
                map.setCenter(waypoints[0]);

                // Content Panel und Seitenmenü schließen.
                $("#content_panel_close").click();
                $("#back").click();
            }
        })
    });
</script>
