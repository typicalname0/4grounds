<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
        <?php
            require(__DIR__ . "/../func/func.php");
            require(__DIR__ . "/../func/conn.php"); 
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/../important/header.php"); 
        
        if(@$_POST['submit']) {
            $register = require(__DIR__ . "/../func/upload.php");
            $register("video", ["mp4"], $conn);
        }
        ?>
        
        <div class="container"><br>
            <form method="post" enctype="multipart/form-data">
				<small>Select a MP4 file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <input type="checkbox" name="remember"><small>This video is <b>NOT</b> a shitpost.</small>
                <hr>
                <input size="69" type="text" placeholder="Video Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your Video" name="description"></textarea><br><br>
                <input type="submit" value="Upload Video" name="submit">  <small>Note: Videos are manually approved.</small>
            </form>
        </div>
    </body>
</html>
