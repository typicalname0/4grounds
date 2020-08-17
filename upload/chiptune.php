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
            $register("chiptune", ["xm", "mod", "s3m", "it"], $conn);
        }
        ?>
        
        <div class="container"><br>
            <form method="post" enctype="multipart/form-data">
				<small>Select a Chiptune file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <hr>
                <input size="69" type="text" placeholder="Song Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your song" name="description"></textarea><br><br>
                <input type="submit" value="Upload Chiptune" name="submit">  <small>Note: Songs are manually approved.</small>
            </form>
        </div>
    </body>
</html>
