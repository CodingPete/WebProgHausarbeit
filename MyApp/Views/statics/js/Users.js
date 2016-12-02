/**
 * Created by peter on 23.11.2016.
 */
function register(form) {
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        beforeSend: function(xhr) {
            var formData = form.serializeArray();
            var password1 = formData[2].value;
            var password2 = formData[3].value;

            if(password1 != password2) {
                alert("Passwörter stimmen nicht überein!");
                xhr.abort();
            }
        },
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
        type: form.attr("method"),
        url: form.attr("action"),
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                alert("Login!");
                location.reload();
            }
            else alert("Login fehlgeschlagen!");
        }
    });
}

function logout(DomElement) {
    console.log("Logout");
    $.ajax({
        type: "POST",
        url: DomElement.attr("action"),
        data: {},
        success: function() {
            alert("Benutzer ausgeloggt");
            location.reload();
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

    $(".logout").on("click", function() {
       logout($(this)) ;
    });
});

