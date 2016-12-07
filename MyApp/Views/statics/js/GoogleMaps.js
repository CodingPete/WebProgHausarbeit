/**
 * Created by peter on 25.11.2016.
 */
var map;
var map_pos;
var current_track;
var track_viewed;
var track_viewed_waypoints;
var track_id_viewed;
var center = true;
var bounds;
var public_markers = new Array();
var public_track;

function initMap() {


    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644},
        scrollwheel: false,
        zoom: 16,
        disableDefaultUI: true,
        streetViewControl: false
    });

    map_pos = new google.maps.Marker({
        position: {lat: -34.397, lng: 150.644},
        map: map,
        title: 'Hello World!',
        icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
    });

    current_track = new google.maps.Polyline({
        strokeColor: "#0000FF",
        strokeOpacity: 1.0,
        strokeWeight: 3,
        editable: false
    });
    current_track.setMap(map);

    track_viewed = new google.maps.Polyline({
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 3,
        editable: true,
        suppressUndo: true
    });
    track_viewed.setMap(map);

    public_track = new google.maps.Polyline({
        strokeColor: "#00FF00",
        strokeOpacity: 1.0,
        strokeWeight: 3,
        editable: false
    });
    public_track.setMap(map);

    // Event um Track zu ändern
    google.maps.event.addListener(track_viewed, 'mouseup', function () {

        setTimeout(function () {
            // Wegpunkte der Polyline mit den ursprünglichen Wegpunkten abgleichen
            var poly_path = track_viewed.getPath();

            for (var i = 0; i < poly_path.b.length; i++) {
                if (i < track_viewed_waypoints.length) {
                    track_viewed_waypoints[i].lat = poly_path.b[i].lat();
                    track_viewed_waypoints[i].lng = poly_path.b[i].lng();
                }
                else {
                    track_viewed_waypoints.push({
                        lat: poly_path.b[i].lat(),
                        lng: poly_path.b[i].lng()
                    });
                }

            }

            $.ajax({
                url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_update_track",
                method: "POST",
                data: {
                    track_id: track_id_viewed,
                    user_id: $("#nickname").text(),
                    waypoints: track_viewed_waypoints,
                    waypoints_enc: google.maps.geometry.encoding.encodePath(track_viewed.getPath())
                },
                success: function () {

                }
            })
        }, 1000);

    });


}

// Alle 200ms die aktuelle Position auf Karte zeichnen.
setInterval(function () {

    // Aktuelle Position des Users
    var position = {
        lat: gps.x(),
        lng: gps.y()
    };
    if (center) map.setCenter(position);
    map_pos.setPosition(position);

    // Die Bounds der Karte holen
    bounds = map.getBounds();
}, 200);


// Automatische Kartenzentrierung ausschalten / Einschalten und GUI-Element färben
$(document).ready(function () {
    $("#center").on("click", function () {
        toggle_centering();
    })
});

function set_centering(setting) {
    if (setting) {
        $("#center").css("color", "inherit");
        center = true;
    }
    else {
        $("#center").css("color", "darkgrey");
        center = false;
    }
}

function toggle_centering() {
    set_centering(!center);
}

// todo: Public-Tracks die auf der Karte liegen zeigen
setInterval(function () {



    var bounds = {
        ne: {
            lat: map.getBounds().getNorthEast().lat(),
            lng: map.getBounds().getNorthEast().lng()
        },
        sw: {
            lat: map.getBounds().getSouthWest().lat(),
            lng: map.getBounds().getSouthWest().lng()
        }

    };

    // Jetzt alle public Tracks holen, deren
    // lat kleiner gleich ne_lat
    // lat größer gleich sw_lat
    // lng kleiner gleich ne_lng
    // lng größer gleich sw_lng ist
    $.ajax({
        url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_get_public_tracks_in_bounds",
        method: "POST",
        data: {
            bounds: bounds
        },
        dataType: "json",
        success: function(response) {
            for(var i = 0; i < public_markers.length; i++) {
                public_markers[i].setMap(null);
            }
            public_markers = new Array();
            for(var i = 0; i < response.length; i++) {
                var location = response[i].startpoint;
                location.lat = parseFloat(location.lat);
                location.lng = parseFloat(location.lng);
                var user_id = response[i].user_id;
                var track_id = response[i].track_id;

                var marker = new google.maps.Marker({
                    position: location,
                    title: user_id,
                    map: map,
                    user_id: user_id,
                    track_id: track_id
                });

                marker.addListener("click", function() {
                    var controller = "Tracks";
                    var func = "view_public_track";

                    var data = {
                        user_id: this.user_id,
                        track_id: this.track_id
                    };

                    load_content(controller, func, data);
                });
                public_markers.push(marker);
            }

            // Alte Marker löschen

            // Neue Marker zeichnen

            // EventListener setzen
        }
    })
}, 5000);

