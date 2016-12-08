<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 08.12.2016
 * Time: 12:34
 */
?>
<div>
    <form action="<?= APP_DOMAIN . "index.php?c=Tracks&f=upload_xml";?>" method="post" enctype="multipart/form-data">
        GPX Upload
        <input type="file" name="gpx_file" id="gpx_file">
        <input type="submit" value="Upload GPX" name="submit">
    </form>
</div>
