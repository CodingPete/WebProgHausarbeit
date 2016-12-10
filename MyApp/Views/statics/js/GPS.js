/**
 * Created by peter on 25.11.2016.
 */

GPS = function() {
    var latitude = 0;
    var longitude = 0;
    var altitude = 0;
    var velocity = 0;

    var watch_id;

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

    getLocation();
};


var gps = new GPS();
