/**
 * Created by peter on 02.12.2016.
 */
var Recorder = function () {

    var IDLE = 0;
    var RECORDING = 1;
    var PAUSE = 2;
    var STOP = 3;

    var state = IDLE;

    var view = "#recorder";

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
                // Tu nix: Wird direkt bei Stopclick direkt ausgeführt, sonst Gefahr, dass das Interval und Buttonklick sich überschneiden
                //go_to_stop();
                break;
            default:
                go_to_idle();
                break;
        }
    };

    $(document).on("click", "#record", function () {
        state = RECORDING;
        go_to_recording();
    });
    $(document).on("click", "#play", function () {
        state = RECORDING;
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


    // Alle 10 Sekunden wird die Statemachine neu aufgerufen um ggf Wegpunkte aufzuzeichnen.
    setInterval(this.state_machine, 10000);

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
            timestamp: Date.now()
        };

        current_track.getPath().push(new google.maps.LatLng(gps.x(), gps.y()));
        waypoints.push(waypoint);
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
            url: "/index.php?c=Tracks&f=ajax_create_track",
            data: {
                payload: JSON.stringify({
                    track_id: 'dont-care',
                    user_id: $("#nickname").text(),
                    waypoints: waypoints,
                    privacy: 'private'
                })
            },
            success: function (response) {
                if (response == "true") {
                    $(view).html("<p>Upload erfolgreich!</p>");
                    var message = "<p>Upload erfolgreich!</p>";
                }
                else {
                    var message = "<p>Fehler: Abbruch!</p>";
                }
                setTimeout(function () {
                    $(view).html(message);
                    current_track.getPath().clear();
                }, 2000);
                state = IDLE;
            },
            error: function () {
                $(view).text("<p>Fehler: Versuche erneut</p>");
            }
        });
    };

};

var recorder = new Recorder();
