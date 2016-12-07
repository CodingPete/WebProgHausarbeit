<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 20:35
 */
?>
<style>

    #page_container {
        max-width: 1400px;
        text-align: center;
        margin: auto;
    }

    .card-img-overlay {
        padding-bottom: 0;
        padding-top: 0;
    }

    .first_info_text_container {
        margin-top: 100px;
        background-color: transparent !important;
        height: 50% !important;
        text-align: center;
    }

    #app_logo {
        width: 40%;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    h1, h4 {
        margin: 0 auto;
        border-radius: 5px;
    }

    .info_text_container {
        color: #FFFFFF;
        background-color: rgb(106, 128, 167);
        height: 100%;
        text-align: center;
    }

    .card-img {
        width: 100%;
        border-radius: 0px;
        opacity: 0.6;
    }

    .card {
        margin: 0 auto;
        border: none;
        border-radius: 0px;
        background-color: rgb(47, 59, 80);
    }

    .stuff {
        color: #FFFFFF;
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        flex-wrap: wrap;
    }

    .stuff > * {
        padding: 1.2em;
    }

    .stuff > .social_network:hover {
        background-color: rgba(106, 128, 167, 0.2);
        border-radius: 5px;
        cursor: pointer;
    }

    #map {
        height: 100%;
    }
</style>
<div class="container-fluid">

    <form class="register_form">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <input type="password" name="password_validation" placeholder="Passwort erneut eingeben" required>
        <input type="submit" value="Registrieren">
    </form>
    <form class="login_form">
        <input type="text" name="username" placeholder="Dein Nickname" required>
        <input type="password" name="password" placeholder="Dein Password" required>
        <input type="submit" value="Login">
    </form>

</div>
