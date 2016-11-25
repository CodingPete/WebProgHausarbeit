/**
 * Created by peter on 25.11.2016.
 */
GPS = function() {
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
