/**
 * Created by peter on 25.11.2016.
 */
var map;
var map_pos;
var current_track;

function initMap() {

    // todo: Letzte Position des Users aus der Datenbank holen und Karte darauf zentrieren

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
        editable: true
    });
    current_track.setMap(map);

}

// Alle 200ms die aktuelle Position auf Karte zeichnen.
setInterval(function() {

    // Aktuelle Position des Users
    var position = {
        lat: gps.x(),
        lng: gps.y()
    };
    map.setCenter(position);
    map_pos.setPosition(position);
}, 200);

