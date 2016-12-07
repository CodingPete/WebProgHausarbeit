<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 12:54
 */
$static_maps_url = "https://maps.googleapis.com/maps/api/staticmap?size=400x200&path=weight:3%7Ccolor:0xff0000ff%7Cenc:";
$static_maps_key = "&key=AIzaSyBep0qQqNBiTtiXlvguRKrWj-UXIBQySEM";
?>
<style>
    select {
        color: #000000;
        width: 100%;
    }

    .track_entry {
        margin-bottom: 30px;
        text-align: center;
    }
    .track_img {
        cursor: pointer;
    }
</style>
<?php foreach ($tracklist as $track): ?>
    <div class="track_entry" id="track_entry_<?= $track["track_id"]; ?>">
        <div>
            <?php if(isset($track["waypoints_enc"])): ?>
            <img class="<?php if($track["user_id"] == $user_id) echo "track_img_mine"; else echo "track_img_other"; ?>" src="<?= $static_maps_url . $track["waypoints_enc"] . $static_maps_key; ?>"
                 user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>">
            <?php endif; ?>
        </div>
        <div>
            <p>
                <?php if($track["user_id"] == $user_id): ?>
                Dein Track vom <?= date("d.m. H:i", $track["starttime"]); ?> Uhr
                <?php else: ?>
                <?=$track["user_id"];?>'s Track vom Track vom <?= date("d.m. H:i", $track["starttime"]); ?> Uhr
                <?php endif; ?>
            </p>
        </div>
        <?php if($track["user_id"] == $user_id): ?>
        <div>
            <select user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>">
                <option value="<?= $track["privacy"]; ?>">
                    <?php
                    if ($track["privacy"] == "private") echo "Nicht teilen";
                    else echo "Teilen";
                    ?>
                </option>
                <option value="<?php
                if ($track["privacy"] == "private") echo "public";
                else echo "private"
                ?>">
                    <?php
                    if ($track["privacy"] == "private") echo "Teilen";
                    else echo "Nicht teilen";
                    ?>
                </option>
            </select>
        </div>
        <?php endif; ?>
        <button class="btn btn-secondary collapse_stats" type="button" data-toggle="collapse"
                data-target="#stats_<?= $track["track_id"]; ?>" aria-expanded="false"
                aria-controls="#stats_<?= $track["track_id"]; ?>">
            Statisik ansehen
        </button>
        <div class="collapse" id="stats_<?= $track["track_id"]; ?>" user="<?= $track["user_id"]; ?>"
             track="<?= $track["track_id"]; ?>">

        </div>
    </div>
<?php endforeach; ?>
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

    $(".track_img_mine").on("click", function () {

        set_centering(false);

        // Laden des ausgewählten Tracks.
        var track_id = $(this).attr("track");
        var user_id = $(this).attr("user");
        track_id_viewed = track_id;
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

    $(".track_img_others").on("click", function () {

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

    $(document).on("click", ".delete", function () {
        var user_id = $(this).attr("user");
        var track_id = $(this).attr("track");

        $.ajax({
            url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_delete_track",
            method: "POST",
            data: {
                user_id: user_id,
                track_id: track_id
            },
            success: function () {
                $("#track_entry_" + track_id).remove();
            }
        })
    })
</script>
