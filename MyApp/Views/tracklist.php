<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 12:54
 */
?>

<table class="table">
    <thead>
    <tr>
        <th>Tracknummer</th>
        <th>Wegpunkte</th>
        <th>Dauer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tracklist as $track):?>
        <tr>
            <td><?=$track["track_id"]; ?></td>
            <td><?=count(json_decode($track["waypoints"]));?></td>
            <td><?php
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
