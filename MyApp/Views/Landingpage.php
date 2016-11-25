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
    <div class="row">
        <div class="card card-inverse">
            <img class="card-img" src="<?= APP_STATICS; ?>jogger-1763571_1920.jpg">
            <div class="card-img-overlay">
                <div class="info_text_container first_info_text_container">
                    <img id="app_logo" src="<?= APP_STATICS; ?>pictogram-1616719_1280.png">
                    <h1>MyTrack</h1>
                    <h4>Deine ultimative Bewegungs - App</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="page_container">
    <div class="container-fluid">
        <div class="row">
            <div class="card card-inverse">
                <img class="card-img" src="<?= APP_STATICS; ?>jogging-1722552_1920.jpg">
                <div class="card-img-overlay">
                    <div class="col-xs-6 hidden-xs-down"></div>
                    <div class="col-xs-12 col-md-6 info_text_container">
                        <p>Deine Freunde sagen, du solltest mal mehr rausgehen aber du sitzt lieber vor'm
                            Bildschirm?</p>
                        <p>Mit MyTrack kannst du deinen Bildschirm jetzt mitnehmen und deine Fortbewegung
                            aufzeichnen!</p>
                        <p>Lust auf Feedback? Teile deine Strecken mit deinen Freunden via Twitter, Facebook und co.</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="stuff">
            <i class="material-icons" style="font-size: 2em;">directions_run</i>
            <i class="fa fa-motorcycle fa-2x" aria-hidden="true"></i>
            <i class="fa fa-bicycle fa-2x" aria-hidden="true"></i>
            <i class="fa fa-ship fa-2x" aria-hidden="true"></i>
            <i class="fa fa-car fa-2x" aria-hidden="true"></i>
            <i class="fa fa-truck fa-2x" aria-hidden="true"></i>
            <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
            <i class="fa fa-wheelchair-alt fa-2x" aria-hidden="true"></i>
        </div>
        <div class="row">
            <div class="card card-inverse">
                <img class="card-img" src="<?= APP_STATICS; ?>running-573762_1280.jpg">
                <div class="card-img-overlay">
                    <div class="col-xs-12 col-md-6 info_text_container">
                        <p>Laufen ist doof? Dann nimm dein Fahrrad! Lustiger Weise funktioniert das ganze auch mit
                            Motorrad,
                            Auto,
                            Boot
                            und Panzer</p>
                        <a id="register" href="#" >Registrieren</a>
                        <form class="login_form" method="post" action="<?=$login_link; ?>">
                            <input type="text" name="email" placeholder="Deine E-Mailadresse">
                            <input type="password" name="password" placeholder="Dein Password">
                            <input type="submit" value="Login">
                        </form>
                        <div id="map"></div>
                    </div>
                    <div class="col-xs-6 hidden-xs-down"></div>

                </div>
            </div>
        </div>
        <div class="stuff">
            <i class="fa fa-twitter social_network" aria-hidden="true"></i>
            <i class="fa fa-facebook-square social_network" aria-hidden="true"></i>
            <i class="fa fa-tumblr social_network" aria-hidden="true"></i>
            <i class="fa fa-instagram social_network" aria-hidden="true"></i>
        </div>

    </div>
</div>
