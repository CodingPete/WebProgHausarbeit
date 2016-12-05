/**
 * Created by peter on 05.12.2016.
 */
$(document).ready(function() {

    $(".side_bar_link").on("click", function() {

        var content_panel = $("#content_panel");

        if(content_panel.css("display") == "none") {
            content_panel.show();

            content_panel.animate({
                right: "0"
            }, 500, function() {})

        }
        else {
            content_panel.animate({
                right: "-100%"
            }, 500, function() {
                content_panel.hide();
            });
        }
    });

    $("#content_panel_close").on("click", function() {
        var content_panel = $("#content_panel");

        content_panel.animate({
            right: "-100%"
        }, 500, function() {
            content_panel.hide();
        });
    });
});


