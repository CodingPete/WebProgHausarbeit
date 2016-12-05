/**
 * Created by peter on 25.11.2016.
 */
GPS = function() {
    var latitude = 0;
    var longitude = 0;
    var counter = 0;

    var positions = [
        {
            coords: {
                latitude: 54.7757,
                longitude: 9.4481
            }
        },
        {
            coords: {
                latitude: 54.77543,
                longitude: 9.44874
            }
        },
        {
            coords: {
                latitude: 54.77526,
                longitude: 9.44941
            }
        },
        {
            coords: {
                latitude: 54.77572,
                longitude: 9.45106
            }
        },
        {
            coords: {
                latitude: 54.77626,
                longitude: 9.45145
            }
        }
    ];

    var getLocation = function() {
        if(navigator.geolocation) {
            //navigator.geolocation.getCurrentPosition(savePosition);
        }
        savePosition(positions[counter]);
        counter = (counter + 1) % 5;

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
