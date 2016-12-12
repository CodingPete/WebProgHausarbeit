<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 02.12.2016
 * Time: 13:4*/
?>
<style>
    #map {
        position: fixed !important;
        left: 0;
        top: 0;
        height: 100vh;
        width: 100vw;
    }
    .gm-style-mtc {
        display: none;
    }
    #ClearPath{
        padding: 15px;
        display: none;
        position: fixed;
        top: 80%;
        left: 80%;
        background-color: #FF0000;
        cursor: pointer;
        z-index: 2;
        border-radius: 100%;
    }
    #overlay {
        z-index: 2;
        position: fixed;
        top: 95%;
        width: 100%;
        bottom: 0%;
        background-color: rgb(47, 59, 80);
        font-size: 0.7em;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
</style>
<div id="map"></div>

<div id="ClearPath">X</div>

<div id="overlay">
    <span id="geschwindigkeit">Geschwindigkeit : </span>
    <span id="hoehe">Höhe : </span>
</div>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiI6HJSMOEJ9_uAIDfYJRqokSqI1FT3RM&libraries=drawing&callback=initMap"
        async defer></script>