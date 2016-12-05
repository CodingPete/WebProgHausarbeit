<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 05.12.2016
 * Time: 12:11
 */?>

<style>

    #content_panel {
        width: 100%;
        height: 100vh;
        z-index: 6;
        position: fixed;
        top: 0;
        right: -100%;
        background-color: rgba(47, 59, 80, 1);
        display: none;
        overflow-y: auto;
        overflow-x: hidden;
    }

    #content_panel_container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 10px;
    }

    #content_panel_close {
        cursor: pointer;
        padding-bottom: 30px;
    }

</style>

<div id="content_panel">
    <div id="content_panel_container">
        <i class="fa fa-arrow-left fa-2x" id="content_panel_close" aria-hidden="true"></i>
        <div id="ajax_container">

        </div>
    </div>
</div>
