/**
 * Created by peter on 25.11.2016.
 */

GPS = function() {
    // Variablen um Werte abzulegen
    var latitude = 0;
    var longitude = 0;
    var altitude = 0;
    var velocity = 0;

    var watch_id;

    var dis = this;

    // Funktion die vom navigator aufgerufen wird, wenn sich die Koordinaten verändern.
    var savePosition = function(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        altitude = position.coords.altitude;
        velocity = position.coords.speed;
    };

    // Latitude ausgeben
    this.x = function() {
        return latitude;
    };

    // Longitude ausgeben
    this.y = function() {
        return longitude;
    };

    // Höhe ausgeben
    this.z = function() {
        return altitude;
    };

    // Geschwindigkeit ausgeben
    this.v = function() {
        return velocity;
    };


    // Fehlerfunktion, falls kein GPS vorliegt
    var errorPosition = function() {
        console.log("Kein GPS");
    };

    // Parameter für watchPosition
    var options = {
        enableHighAccuracy: true,
        maximumAge        : 30000,
        timeout           : 27000
    };

    // Instanzieren der watchPosition
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
