/**
 * Created by peter on 02.12.2016.
 */
var Recorder = function () {

    IDLE = 0;
    RECORDING = 1;
    PAUSE = 2;
    STOP = 3;

    var state = IDLE;

    this.get_state = function() {
        return state;
    };

    var view = "#recorder";

    var is_start = true;

    var waypoints = [];

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

    $(document).on("click", "#record", function () {
        is_start = true;
        state = RECORDING;
        go_to_recording();
    });
    $(document).on("click", "#play", function () {
        state = RECORDING;
        is_start = true;
        go_to_recording();
    });
    $(document).on("click", "#pause", function () {
        state = PAUSE;
        go_to_pause();
    });
    $(document).on("click", "#stop", function () {
        state = STOP;
        go_to_stop();
    });


    // Alle 5 Sekunden wird die Statemachine neu aufgerufen um ggf Wegpunkte aufzuzeichnen.
    setInterval(this.state_machine, 5000);

    this.getState = function() {
        return state;
    };

    var go_to_idle = function() {
        $(view).html('' +
            '<i id="record" class="fa fa-circle recorder_controls" aria-hidden="true"></i>');
    };

    var go_to_recording = function() {
        $(view).html('' +
            '<i id="pause" class="fa fa-pause recorder_controls" aria-hidden="true"></i>' +
            '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');

        var waypoint = {
            lat: gps.x(),
            lng: gps.y(),
            alt: gps.z(),
            speed: gps.v(),
            timestamp: Date.now(),
            is_start: is_start
        };

        current_track.getPath().push(new google.maps.LatLng(gps.x(), gps.y()));
        waypoints.push(waypoint);

        is_start = false;

    };

    var go_to_pause = function() {
        $(view).html('' +
            '<i id="play" class="fa fa-play recorder_controls" aria-hidden="true"></i>' +
            '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');
    };

    var go_to_stop = function() {
        $(view).html('<p>Upload...</p>');

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
                if (response == "true") {
                    var message = "<p>Upload erfolgreich!</p>";
                    $(view).html(message);
                    current_track.getPath().clear();
                    state = IDLE;
                }
                else {
                    var message = "<p>Fehler: Abbruch!</p>";
                    state = IDLE;
                }
                setTimeout(function () {
                    $(view).html(message);
                }, 2000);
            },
            error: function () {
                $(view).html("<p>Fehler: Versuche erneut</p>");
                state = STOP;
            }
        });
    };

};

var recorder = new Recorder();
