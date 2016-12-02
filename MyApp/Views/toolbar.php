<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 16.11.2016
 * Time: 20:55
 */?>
<style>
    #menu-bar {
        width: 100%;
        background-color: transparent;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        padding: 5px;
        position: absolute;
        top: 0;
        left: 0;
        z-index:5;
    }
    #menu-bar > i {
        margin-right: auto;
        cursor: pointer;
        padding: 10px;
    }
    #menu-bar > i:hover {
        background-color: rgba(106,128,167, 0.2);
        border-radius: 5px;
    }
    #menu-bar > a {
        padding: 10px;
        color: saddlebrown;
    }
    #menu-bar > a:hover {
        background-color: rgba(106,128,167, 0.2);
        border-radius: 5px;
    }
</style>
<div id="menu-bar">
    <i id="burger" class="fa fa-bars fa-2x" aria-hidden="true"></i>
    <a href="#">Startseite</a>
    <a href="#">Strecken</a>
    <a href="#">Warum</a>
</div>
