/**
 * Created by peter on 23.11.2016.
 */
function register(form, link) {
    $.ajax({
        type: "POST",
        url: link,
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                alert("registriert!");
            }
            else alert("Registration fehlgeschlagen!");
        }
    });
}

function login(form, link) {
    $.ajax({
        type: "POST",
        url: "<?=$login_link; ?>",
        data: form.serialize(),
        success: function(response) {
            if(response == "true") {
                alert("Login!");
            }
            else alert("Login fehlgeschlagen!");
        }
    });
}