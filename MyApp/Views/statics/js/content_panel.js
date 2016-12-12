/**
 * Created by peter on 05.12.2016.
 */
var load_content;
$(document).ready(function() {

    // Wenn auf einen Link im Seitenmenü geklickt wird
    $(".side_bar_link").on("click", function() {

        // Wenn es nicht der Logoutbutton war
        if($(this).attr("id") === "logout") return;

        // Zielparameter holen
        var controller = $(this).attr("controller");
        var func = $(this).attr("func");

        // Den Inhalt laden
        load_content(controller, func);


    });

    // Pfeil zurück
    $("#content_panel_close").on("click", function() {
        var content_panel = $("#content_panel");

        content_panel.animate({
            right: "-100%"
        }, 500, function() {
            content_panel.hide();
        });
    });

    // Wenn man auf das Profilbild clickt
    $("#side_bar_profile").on("click", function() {

        // Formular für Avatarupload laden.
        load_content("Users", "ajax_upload_avatar_html");
    });

    // Funktion um Inhalt per Ajax nachzuladen und ins ContentPanel zu schreiben.
    load_content = function(controller, func, data) {

        var content_panel = $("#content_panel");

        // Wenn das Content_Panel nicht zu sehen ist.
        if(content_panel.css("display") == "none") {

            // Inhalt per Ajax ins content_panel laden
            $("#ajax_container").html("");
            $.ajax({
                type: "POST",
                url: APP_DOMAIN + "index.php?c="+controller+"&f="+func,
                data: data,
                success: function (response) {
                    // Response ins Content Panel schreiben.
                    $("#ajax_container").html(response);
                }
            });

            // Zeige das Contentpanel
            content_panel.show();

            // Schiebe das Contentpanel ins Bild
            content_panel.animate({
                right: "0"
            }, 500, function() {})

        }
        // Wenn das Contentpanel nicht zu sehen ist.
        else {
            // Fahre es zurück ins off
            content_panel.animate({
                right: "-100%"
            }, 500, function() {
                content_panel.hide();
            });
        }
    }
});


