<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 12:54
 */
?>
<style>
    select {
        color: #000000;
    }
</style>
<table class="table">
    <thead>
    <tr>
        <th>Tracknummer</th>
        <th>Sichtbarkeit</th>
        <th>Dauer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tracklist as $track): ?>
        <tr>
            <td><?= $track["track_id"]; ?></td>
            <td>
                <select user="<?= $track["user_id"]; ?>" track="<?= $track["track_id"]; ?>">
                    <option><?= $track["privacy"]; ?></option>
                    <option>
                        <?php
                        if ($track["privacy"] == "private") echo "public";
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
            url: "/index.php?c=Tracks&f=ajax_update_track",
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
</script>
