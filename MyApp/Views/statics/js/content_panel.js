/**
 * Created by peter on 05.12.2016.
 */
$(document).ready(function() {

    $(".side_bar_link").on("click", function() {

        var target = $(this).attr("id");

        var content_panel = $("#content_panel");

        if(content_panel.css("display") == "none") {

            // Inhalt per Ajax ins content_panel laden
            $("#ajax_container").html("");
            $.ajax({
                type: "POST",
                url: "/index.php?c=Tracks&f="+target,
                success: function (response) {
                    $("#ajax_container").html(response);
                }
            });

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


