<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require("func/func.php");
            require("func/conn.php"); 
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require("important/header.php"); 
        
        if(@$_POST['submit']) {
            $upload = require('../func/upload.php');
            $upload('images', 'image', ["gif", "png", "jpg", "jpeg"]);
        }
        ?>
        
        <div class="container"><br>
            <form method="post" enctype="multipart/form-data">
				<small>Select a Image file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <hr>
                <input size="69" type="text" placeholder="Image Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your image" name="description"></textarea><br><br>
                <input type="submit" value="Upload Image" name="submit">  <small>Note: Images are manually approved.</small>
            </form>
        </div>
    </body>
</html>