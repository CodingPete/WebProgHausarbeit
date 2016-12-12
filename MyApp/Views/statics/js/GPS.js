/**
 * Created by peter on 25.11.2016.
 */

GPS = function() {
    var latitude = 0;
    var longitude = 0;
    var altitude = 0;
    var velocity = 0;

    var watch_id;

    var dis = this;

    var savePosition = function(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        altitude = position.coords.altitude;
        velocity = position.coords.speed;
    };

    this.x = function() {
        return latitude;
    };
    this.y = function() {
        return longitude;
    };
    this.z = function() {
        return altitude;
    };
    this.v = function() {
        return velocity;
    };


    var errorPosition = function() {
        console.log("Kein GPS");
    };

    var options = {
        enableHighAccuracy: true,
        maximumAge        : 30000,
        timeout           : 27000
    };

    var getLocation = function() {
        if(navigator.geolocation) {
            watch_id = navigator.geolocation.watchPosition(savePosition, errorPosition, options);
        }

    };

    setInterval(function() {
        // Werte ins Overlay schreiben
        if(!isNaN(dis.v()) && dis.v() != null)
            $("#geschwindigkeit").text("Geschwindkeit : " + dis.v());
        if(!isNaN(dis.z()) && dis.z() != null)
            $("#hoehe").text("Höhe : " + dis.z());
    }, 200);

    getLocation();
};


var gps = new GPS();
