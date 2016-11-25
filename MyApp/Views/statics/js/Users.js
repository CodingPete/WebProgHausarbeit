/**
 * Created by peter on 23.11.2016.
 */
function register(form) {
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                alert("registriert!");
            }
            else alert("Registration fehlgeschlagen!");
        }
    });
}

function login(form) {
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                alert("Login!");
            }
            else alert("Login fehlgeschlagen!");
        }
    });
}

function logout(DomElement) {
    $.ajax({
        type: "POST",
        url: DomElement.attr("action"),
        data: {},
        success: function() {
            alert("Benutzer ausgeloggt");
        }
    })
}

$(document).ready(function() {

    $(".register_form").on("submit", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        register($(this));
    });

    $(".login_form").on("submit", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        login($(this));
    });
});

