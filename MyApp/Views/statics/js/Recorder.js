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

    var state_machine = function () {
        switch (state) {
            case IDLE:
                $(view).html('' +
                    '<i id="record" class="fa fa-circle recorder_controls" aria-hidden="true"></i>');
                break;
            case RECORDING:
                $(view).html('' +
                    '<i id="pause" class="fa fa-pause recorder_controls" aria-hidden="true"></i>' +
                    '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');

                var waypoint = {
                    lat: gps.x(),
                    lng: gps.y(),
                    timestamp: Date.now()
                };

                waypoints.push(waypoint);
                break;
            case PAUSE:
                $(view).html('' +
                    '<i id="play" class="fa fa-play recorder_controls" aria-hidden="true"></i>' +
                    '<i id="stop" class="fa fa-stop recorder_controls" aria-hidden="true"></i>');
                break;
            case STOP:
                $(view).html('Upload...');

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
                            $(view).html("Upload erfolgreich!");
                            var message = "Upload erfolgreich!";
                        }
                        else {
                            var message = "Fehler: Abbruch!";
                        }
                        setTimeout(function () {
                            $(view).text(message);
                        }, 2000);
                        state = IDLE;
                    },
                    error: function () {
                        $(view).text('Fehler: Versuche erneut');
                    }
                });
                break;
            default:
                state = IDLE;
                break;
        }
    };

    $(document).on("click", "#record", function () {
        state = RECORDING;
        $(view).empty();
    });
    $(document).on("click", "#play", function () {
        state = RECORDING;
        $(view).empty();
    });
    $(document).on("click", "#pause", function () {
        state = PAUSE;
        $(view).empty();
    });
    $(document).on("click", "#stop", function () {
        state = STOP;
        $(view).empty();
    });


    setInterval(state_machine, 2000);
};

var recorder = new Recorder();