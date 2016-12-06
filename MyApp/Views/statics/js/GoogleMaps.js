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
        title: 'Hello World!'
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

    // Event um Track zu ändern
    google.maps.event.addListener(track_viewed, 'mouseup', function () {

        setTimeout(function() {
            // Wegpunkte der Polyline mit den ursprünglichen Wegpunkten abgleichen
            var poly_path = track_viewed.getPath();

            for (var i = 0; i < poly_path.b.length; i++) {
                if(i < track_viewed_waypoints.length) {
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
                    waypoints: JSON.stringify(track_viewed_waypoints),
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

// Public-Tracks die auf der Karte liegen zeigen


