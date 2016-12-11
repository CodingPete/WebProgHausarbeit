<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 08.12.2016
 * Time: 12:34
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
    <h1>GPX Upload</h1>
    <form id="xml_upload" enctype="multipart/form-data">
        <input type="file" name="gpx_file" id="gpx_file">
        <input type="submit" value="Upload GPX" name="submit">
    </form>
</div>

<script>
    $("#xml_upload").on("submit", function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();

        var formData = new FormData();
        formData.append('gpx_file', $('#gpx_file')[0].files[0]);

        $.ajax({
            url: APP_DOMAIN + "index.php?c=Tracks&f=upload_xml",
            data: formData,
            processData: false,
            contentType: false,
            method: "POST",
            success: function() {
                $("#content_panel_close").click();
            }
        })
    })
</script>