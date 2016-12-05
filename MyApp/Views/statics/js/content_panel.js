/**
 * Created by peter on 05.12.2016.
 */
$(document).ready(function() {

    $(".side_bar_link").on("click", function() {

        if($(this).attr("id") === "logout") return;

        var controller = $(this).attr("controller");
        var func = $(this).attr("func");

        load_content(controller, func);


    });

    $("#content_panel_close").on("click", function() {
        var content_panel = $("#content_panel");

        content_panel.animate({
            right: "-100%"
        }, 500, function() {
            content_panel.hide();
        });
    });

    $("#side_bar_profile").on("click", function() {

        load_content("Users", "ajax_upload_avatar_html");
    });

    var load_content = function(controller, func) {
        var content_panel = $("#content_panel");

        if(content_panel.css("display") == "none") {

            // Inhalt per Ajax ins content_panel laden
            $("#ajax_container").html("");
            $.ajax({
                type: "POST",
                url: APP_DOMAIN + "index.php?c="+controller+"&f="+func,
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
    }
});


