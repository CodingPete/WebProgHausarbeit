<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 02.12.2016
 * Time: 14:12
 */ ?>
<style>
    #side_bar {
        display: none;
        height: 100vh;
        width: 34vw;
        top: 0;
        position: fixed;
        left: -34vw;
        background-color: rgba(47, 59, 80, 1);
        z-index: 3;
    }

    #side_bar_container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    #side_bar_container > .side_bar_link {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        cursor: pointer;
        padding-left: 5px;
        padding-right: 5px;
    }

    #side_bar_container > .side_bar_link:hover {
        background-color: rgba(67, 79, 100, 0.95);
    }

    #side_bar_container > .side_bar_link > * {
        margin-right: auto;
        margin-top: auto;
        margin-bottom: auto;
    }

    #side_bar_profile {
        display: flex;
        flex-direction: column;
    }

    #side_bar_profile > i {
        margin: auto;
        font-size: 15em;
    }
    .side_bar_logout {
        margin-top: auto;
    }
    #back {
        cursor: pointer;
        padding: 10px;
    }
</style>

<div id="side_bar">
    <div id="side_bar_container">
        <i class="fa fa-arrow-left fa-2x" id="back" aria-hidden="true"></i>
        <div id="side_bar_profile">
            <i class="fa fa-user-circle-o" aria-hidden="true" style=""></i>
        </div>
        <div class="side_bar_link">
            <i class="fa fa-history" aria-hidden="true"></i>
            Meine Tracks
        </div>
        <div class="side_bar_link">
            <i class="fa fa-line-chart" aria-hidden="true"></i>
            Statistik
        </div>

        <div class="side_bar_link side_bar_logout logout" action="<?= $logout_link; ?>">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            Logout
        </div>
    </div>
</div>
