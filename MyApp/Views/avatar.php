<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 18:59
 */
?>
<style>
    .formular {
        text-align: center;
    }

    form {
        margin-bottom: 30px;
    }

    input {
        color: #FFFFFF;
        border: none;
        background-color: rgb(67, 79, 100);
        width: 80%;
    }
</style>

<div class="formular">
    <h1>Avatar Upload</h1>
    <form id="avatar_form">
        <input type="file" name="soonToBeAvatar" id="soonToBeAvatar">
        <input type="submit" value="Hochladen">
    </form>
</div>
<script>
    //Wenn das Avatarupload formular eingereicht wird
    $("#avatar_form").on("submit", function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();

        var avatar;

        // Datei holen
        var avatar_file = document.getElementById("soonToBeAvatar").files[0];
        var file_reader = new FileReader();
        // Bild als Dataurl holen
        file_reader.readAsDataURL(avatar_file);
        file_reader.onload = upload;

        function upload() {
            $.ajax({
                method: "POST",
                url: APP_DOMAIN + "index.php?c=Users&f=ajax_upload_avatar",
                data: {
                    avatar: file_reader.result
                },
                success: function () {
                    // Bei Erfolg Panel schließen
                    $("#content_panel_close").click();
                    // Avatar neuholen
                    $.ajax({
                        method: "POST",
                        url: APP_DOMAIN + "index.php?c=Users&f=ajax_get_avatar",
                        success: function (response) {
                            $("#avatar").attr("src", response);
                        }
                    })
                }
            })
        }
    });

</script>