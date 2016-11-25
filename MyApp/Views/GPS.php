<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 12.11.2016
 * Time: 16:06
 */
?>
<script type="text/javascript">
    var GPS = function() {
        var latitude = 0;
        var longitude = 0;

        var getLocation = function() {
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(savePosition);
            }
        };
        var savePosition = function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
        };

        this.x = function() {
            return latitude;
        };
        this.y = function() {
            return longitude;
        };

        setInterval(getLocation, 500);
    };

    var gps = new GPS();


</script>

