<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 07.12.2016
 * Time: 12:44
 */
$static_maps_url = "https://maps.googleapis.com/maps/api/staticmap?size=400x200&path=weight:3%7Ccolor:0xff0000ff%7Cenc:";
$static_maps_key = "&key=AIzaSyBep0qQqNBiTtiXlvguRKrWj-UXIBQySEM";
?>
<style>
    .track_entry {
        margin-bottom: 30px;
        text-align: center;
    }
    .track_img {
        cursor: pointer;
    }
</style>

<div class="track_entry" id="track_entry_<?= $track["track_id"]; ?>">
    <div>
        <?php if(isset($track["waypoints_enc"])): ?>
            <img class="track_img" src="<?= $static_maps_url . $track["waypoints_enc"] . $static_maps_key; ?>"
                 user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>">
        <?php endif; ?>
    </div>
    <div>
        <p>
            <?=$track["user_id"];?>'s Track vom <?= date("d.m. H:i", $track["starttime"]); ?> Uhr
        </p>
    </div>
    <button class="btn btn-secondary collapse_stats" type="button" data-toggle="collapse"
            data-target="#stats_<?= $track["track_id"]; ?>" aria-expanded="false"
            aria-controls="#stats_<?= $track["track_id"]; ?>">
        Statisik ansehen
    </button>
    <div class="collapse" id="stats_<?= $track["track_id"]; ?>" user="<?= $track["user_id"]; ?>"
         track="<?= $track["track_id"]; ?>">

    </div>
</div>

<script>
    $(".track_img").on("click", function () {

        set_centering(false);

        // Laden des ausgewählten Tracks.
        var track_id = $(this).attr("track");
        var user_id = $(this).attr("user");

        $.ajax({
            type: "POST",
            url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_get_track",
            dataType: "json",
            data: {
                user_id: user_id,
                track_id: track_id
            },
            success: function (response) {
                var waypoints = response.waypoints;

                // Bisherigen Pfad löschen
                public_track.getPath().clear();
                // Wegpunkte in die Karte zeichnen
                public_track.setPath(waypoints);

                // Karte auf Anfangspunkt des Tracks zentrieren.
                map.setCenter(waypoints[0]);

                // Content Panel schließen.
                $("#content_panel_close").click();
            }
        })
    });

    $(".collapse_stats").on("click", function () {
        var area = $(this).next();

        var user_id = area.attr("user");
        var track_id = area.attr("track");

        $.ajax({
            url: APP_DOMAIN + "index.php?c=Statistics&f=get_track_stats",
            method: "POST",
            data: {
                user_id: user_id,
                track_id: track_id
            },
            success: function (response) {
                area.html(response);
            }
        });
    });
</script>