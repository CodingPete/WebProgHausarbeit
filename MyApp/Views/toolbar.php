<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 16.11.2016
 * Time: 20:55
 */ ?>
<style>
    #menu-bar {
        width: 100%;
        background-color: rgba(47, 59, 80, 1);
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        padding: 5px;
        position: relative;
        top: 0;
        left: 0;
        z-index: 2;
    }

    #menu-bar > #burger {
        margin-right: auto;
        cursor: pointer;
        padding: 10px;
    }

    #menu-bar > #burger:hover {
        background-color: rgba(106, 128, 167, 0.2);
        border-radius: 5px;
    }

    #menu-bar > a {
        padding: 10px;
        color: saddlebrown;
    }

    #menu-bar > a:hover {
        background-color: rgba(106, 128, 167, 0.2);
        border-radius: 5px;
    }

    #menu-bar > span {
        max-width: 50%;
    }
    #recorder {
        margin-left: auto;
        flex-grow: 10;
        display: flex;
        flex-direction: row;
        justify-content: center;


    }
    .recorder_controls {
        margin: auto;
        cursor: pointer;
        padding: 10px;
    }
</style>
<div id="menu-bar">
    <i id="burger" class="fa fa-bars fa-2x" aria-hidden="true"></i>
        <span id="recorder">

        </span>
</div>
