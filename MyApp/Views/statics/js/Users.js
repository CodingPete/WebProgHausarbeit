/**
 * Created by peter on 23.11.2016.
 */
function register(form) {
    $.ajax({
        method: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=register",
        data: form.serialize(),
        beforeSend: function (xhr) {

            // Vor dem Senden Daten prüfen
            var formData = form.serializeArray();
            var password1 = formData[1].value;
            var password2 = formData[2].value;

            if (password1 != password2) {
                alert("Passwörter stimmen nicht überein!");
                xhr.abort();
            }
        },
        success: function (response) {
            // Wenn erfolgreich regisriert, ...
            if (response == "true") {
                // Bescheid geben
                alert("registriert!");
                // Loginfunktion ausführen
                login(form);
            }
            else alert("Registration fehlgeschlagen!");
        }
    });
}

function login(form) {
    $.ajax({
        method: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=login",
        data: form.serialize(),
        success: function (response) {
            // Wenn erfolgreich eingeloggt
            if (response == "true") {
                // ... Seite aktualisieren
                window.location.reload();
            }
            else alert("Login fehlgeschlagen!");
        }
    });
}

function logout() {
    console.log("Logout");
    $.ajax({
        method: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=logout",
        data: {},
        success: function () {

            window.location.reload();
        }
    })
}

$(document).ready(function () {

    // Wenn Regstrierformular eingereicht wird
    $(".register_form").on("submit", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        register($(this));
    });

    // Wenn Loginformular eingereicht wird
    $(".login_form").on("submit", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        login($(this));
    });

    // Wenn auf Logout geklickt wird
    $(".logout").on("click", function () {
        logout();
    });

    // Hole den Avatar des Nutzers
    $.ajax({
        method: "POST",
        url: APP_DOMAIN + "index.php?c=Users&f=ajax_get_avatar",
        success: function (response) {
            if (response !== "false")
                $("#avatar").attr("src", response);
        }
    })
});

