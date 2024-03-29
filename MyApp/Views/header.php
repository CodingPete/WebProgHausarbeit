<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 20:35
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <title>MyTrack</title>

    <!-- Sachen von anderen Leuten -->
    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Meine heiligen Javamanusskripte -->
    <script src="<?= APP_STATICS; ?>js/Users.js"></script>
    <?php if($login): ?>
    <script src="<?= APP_STATICS; ?>js/GPS.js"></script>
    <script src="<?= APP_STATICS; ?>js/GoogleMaps.js"></script>
    <script src="<?= APP_STATICS; ?>js/side_bar.js"></script>
    <script src="<?= APP_STATICS; ?>js/Recorder.js"></script>
    <script src="<?= APP_STATICS; ?>js/content_panel.js"></script>
    <?php endif; ?>
    <script>
        var APP_DOMAIN = "<?=APP_DOMAIN; ?>";
    </script>

    <style>
        body {
            color: #FFFFFF;
            background-color: rgb(47, 59, 80);
            overflow-x: hidden;
            font-size: 1.2em;
            height: 100vh;
        }
        a {
            padding: 1.2em;
            width: 100%;
            margin-bottom: 30px;
            color: #FFFFFF;
        }
        a:visited {
            color: #FFFFFF;
        }

        a:hover: {
            color: #FFFFFF;
        }
        form {
            color: #000000;
        }
    </style>
</head>
<body>