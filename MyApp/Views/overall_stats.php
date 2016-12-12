<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 12.12.2016
 * Time: 10:20
 */ ?>
<style>
    #overall_stats {
        text-align: center;
    }

    button {
        width: 100%;
        color: #FFFFFF;
    }

    .btn {
        background-color: rgba(67, 79, 100, 1);
        border: none;
        color: #FFFFFF;
    }
</style>
<div id="overall_stats">
    Zurückgelegte Distanz : <?= $strecke; ?> Kilometer
    <br>
    Gebrauchte Zeit : <?= $zeit; ?> Minuten
    <br>
    Höchstgeschwindigkeit : <?= $v_max; ?> Km/h
    <br>
</div>
<script>
    // Wenn auf Kontolöschen geklickt wird
    $(document).on("click", ".delete_kto", function () {
        var user_id = $(this).attr("user");

        $.ajax({
            url: APP_DOMAIN + "index.php?c=Users&f=ajax_delete_user",
            method: "POST",
            data: {
                user_id: user_id
            },
            success: function () {
                logout();
            }
        })
    })
</script>
