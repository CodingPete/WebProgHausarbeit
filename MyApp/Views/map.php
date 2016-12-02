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
</style>
<div id="map"></div>


<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiI6HJSMOEJ9_uAIDfYJRqokSqI1FT3RM&callback=initMap"
        async defer></script>