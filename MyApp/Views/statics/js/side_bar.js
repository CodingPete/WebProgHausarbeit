/**
 * Created by peter on 02.12.2016.
 */


$(document).ready(function() {

    // Wenn auf den Burger geklickt wird.
    $("#burger").on("click", function(e) {

        var sidebar = $("#side_bar");
        var blackbar = $("#black_bar");

        if(sidebar.css("display") == "none") {
            sidebar.show();
            blackbar.show();
            sidebar.animate({
                left: "0"
            }, 500, function() {})
            blackbar.animate({
                opacity: "0.7"
            }, 500, function() {})
        }
        else {
            sidebar.animate({
                left: "-60vw"
            }, 500, function() {
                sidebar.hide();
            });
            blackbar.animate({
                opacity: "0"
            }, 500, function() {
                blackbar.hide();
            });
        }
    });

    // Wenn auf zurück geklickt wird.
    $("#back").on("click", function() {
        // Menü einklappen
        $("#burger").click();
    })

    // Wenn schwarzes Overlay geklickt wird.
    $("#black_bar").on("click", function() {
        // Menü einklappen
        $("#burger").click();
    })

});