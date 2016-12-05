/**
 * Created by peter on 23.11.2016.
 */
function register(form) {
    $.ajax({
        type: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=register",
        data: form.serialize(),
        beforeSend: function(xhr) {
            var formData = form.serializeArray();
            var password1 = formData[1].value;
            var password2 = formData[2].value;

            if(password1 != password2) {
                alert("Passwörter stimmen nicht überein!");
                xhr.abort();
            }
        },
        success: function(response) {
            if(response == "true") {
                alert("registriert!");
                login(form);
            }
            else alert("Registration fehlgeschlagen!");
        }
    });
}

function login(form) {
    $.ajax({
        type: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=login",
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                location.reload();
            }
            else alert("Login fehlgeschlagen!");
        }
    });
}

function logout() {
    console.log("Logout");
    $.ajax({
        type: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=logout",
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
       logout() ;
    });

    $.ajax({
        method: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=ajax_get_avatar",
        success: function(response) {
            if(response !== "false")
                $("#avatar").attr("src", response);
        }
    })
});

