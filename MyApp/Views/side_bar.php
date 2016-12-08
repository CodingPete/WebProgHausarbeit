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
        width: 60vw;
        max-width: 600px;
        top: 0;
        position: fixed;
        left: -60vw;
        background-color: rgba(47, 59, 80, 1);
        z-index: 4;
    }

    #nickname {
        word-break: break-all;
    }

    #side_bar_container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    #side_bar_upper {
        width: 100%;
        padding: 10px;
        background-color: rgba(67, 79, 100, 1);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    #side_bar_lower {
        width: 100%;

        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .side_bar_link {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .side_bar_link > * {
        margin-top: auto;
        margin-bottom: auto;
    }

    .side_bar_link > span {
        width: 20px;
        margin-right: 20px;
    }

    #side_bar_profile {
        display: flex;
        flex-direction: column;
        height: 100%;

        margin-bottom: 20px;
    }

    #side_bar_profile > div {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    #side_bar_profile > i {
        font-size: 5em;
    }

    .side_bar_logout {
        margin-top: auto;
    }

    #back {
        cursor: pointer;
        padding-bottom: 30px;
    }

    #black_bar {
        display: none;
        position: fixed;
        width: 100vw;
        height: 100vh;
        z-index: 3;
        opacity: 0;
        background-color: black;
    }
    #avatar {
        width: 20%;
        border-radius: 100%;
        margin-bottom: 30px;
        cursor: pointer;
    }
</style>

<div id="side_bar">
    <div id="side_bar_container">
        <div id="side_bar_upper">
            <i class="fa fa-arrow-left fa-2x" id="back" aria-hidden="true"></i>
            <div id="side_bar_profile">
                <img src="<?= APP_STATICS;?>borstiapache.jpg" id="avatar">
                <div id="nickname"><?= $user_id; ?></div>
            </div>
        </div>
        <div id="side_bar_lower">
            <div class="side_bar_link" controller="Tracks" func="ajax_get_track_list_html">
            <span>
            <i class="fa fa-history" aria-hidden="true"></i>
                </span>
                Meine Tracks
            </div>
            <div class="side_bar_link" controller="Tracks" func="upload_xml_html">
            <span>
            <i class="fa fa-upload" aria-hidden="true"></i>
                </span>
                GPX Upload
            </div>
            <div class="side_bar_link" controller="" func="statistics">
            <span>
            <i class="fa fa-line-chart" aria-hidden="true"></i>
            </span>
                Statistik
            </div>

            <div class="side_bar_link side_bar_logout logout" id="logout">
            <span>
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </span>
                Logout
            </div>
        </div>
    </div>
</div>

<div id="black_bar">

</div>
