/**
 * Created by peter on 02.12.2016.
 */
var Recorder = function () {

    // Die Status in denen sich die Statemachine befinden kann
    IDLE = 0;
    RECORDING = 1;
    PAUSE = 2;
    STOP = 3;

    // Startstate
    var state = IDLE;

    // Liefert den aktuellen State zurück
    this.get_state = function() {
        return state;
    };

    var view = "#recorder";

    var is_start = true;

    var waypoints = [];

    // Die Statemachine
    this.state_machine = function () {
        switch (state) {
            case IDLE:
                go_to_idle();
                break;
            case RECORDING:
                go_to_recording();
                break;
            case PAUSE:
                go_to_pause();
                break;
            case STOP:
                go_to_stop();
                break;
            default:
                go_to_idle();
                break;
        }
    };

    // Wenn auf Aufnehmen geklickt wird
    $(document).on("click", "#record", function () {
        is_start = true;
        state = RECORDING;
        go_to_recording();
    });
    // Wenn auf Abspielen geklickt wird
    $(document).on("click", "#play", function () {
        state = RECORDING;
        is_start = true;
        go_to_recording();
    });
    // Wenn auf Pause geklickt wird
    $(document).on("click", "#pause", function () {
        state = PAUSE;
        go_to_pause();
    });
    // Wenn auf Stop geklickt wird
    $(document).on("click", "#stop", function () {
        state = STOP;
        go_to_stop();
    });


    // Alle 5 Sekunden wird die Statemachine neu aufgerufen um ggf Wegpunkte aufzuzeichnen.
    setInterval(this.state_machine, 5000);

    this.getState = function() {
        return state;
    };

    // Logik für IDLE-STate
    var go_to_idle = function() {
        $(view).html('' +
            '<i id="record" class="fa fa-circle recorder_controls" aria-hidden="true"></i>');
    };

    // Logik für RECORDING-State
    var go_to_recording = function() {
        $(view).html('' +
            '<i id="pause" class="fa fa-pause recorder_controls" aria-hidden="true"></i>' +
            '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');

        // Wegpunkt aufzeichnen
        var waypoint = {
            lat: gps.x(),
            lng: gps.y(),
            alt: gps.z(),
            speed: gps.v(),
            timestamp: Date.now(),
            is_start: is_start
        };

        // Wegpunkt in die Karte zeichnen.
        current_track.getPath().push(new google.maps.LatLng(gps.x(), gps.y()));
        waypoints.push(waypoint);

        // false setzen, da keine Startposition (bspw nach Pause)
        is_start = false;

    };

    // Logik für PAUSE-State
    var go_to_pause = function() {
        $(view).html('' +
            '<i id="play" class="fa fa-play recorder_controls" aria-hidden="true"></i>' +
            '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');
    };

    // Logik für STOP-State
    var go_to_stop = function() {
        $(view).html('<p>Upload...</p>');

        // Hochladen zu MyTrack
        $.ajax({
            type: "POST",
            url: APP_DOMAIN + "index.php?c=Tracks&f=ajax_create_track",
            data: {
                payload: JSON.stringify({
                    track_id: 'dont-care',
                    user_id: $("#nickname").text(),
                    waypoints: waypoints,
                    waypoints_enc: google.maps.geometry.encoding.encodePath(current_track.getPath()),
                    privacy: 'private'
                })
            },
            success: function (response) {
                // Wenn erfolgreich, ...
                if (response == "true") {
                    // Rückmeldung und View anpassen
                    var message = "<p>Upload erfolgreich!</p>";
                    $(view).html(message);
                    current_track.getPath().clear();
                    state = IDLE;
                }
                // ... ansonsten
                else {
                    // Daten fehlerhaft, abruch, zurück in IDLE
                    var message = "<p>Fehler: Abbruch!</p>";
                    state = IDLE;
                }
                setTimeout(function () {
                    $(view).html(message);
                }, 2000);
            },
            error: function () {
                // Keine Verbindung, weiterprobieren
                $(view).html("<p>Fehler: Versuche erneut</p>");
                state = STOP;
            }
        });
    };

};

// Recorder instanzieren
var recorder = new Recorder();
