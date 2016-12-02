/**
 * Created by peter on 02.12.2016.
 */


$(document).ready(function() {

   // $("#side_bar").css("top", $("#menu-bar").css("height"));

    $("#burger").on("click", function(e) {

        var sidebar = $("#side_bar");
        if(sidebar.css("display") == "none") {
            sidebar.show();
            sidebar.animate({
                left: "0"
            }, 500, function() {})
        }
        else {
            sidebar.animate({
                left: "-34vw"
            }, 500, function() {
                sidebar.hide();
            })
        }
    });
});