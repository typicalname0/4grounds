<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
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
            $register("game", ["swf"], $conn);
        }
        ?>
        
        <div class="container"><br>
            <form method="post" enctype="multipart/form-data">
				<small>Select a SWF file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <input type="checkbox" name="remember"><small>This game will not infringe any copyright laws AND is not NSFW</small>
                <hr>
                <input size="69" type="text" placeholder="Game Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your game" name="description"></textarea><br><br>
                <input type="submit" value="Upload Game" name="submit">  <small>Note: Games are manually approved.</small>
            </form>
        </div>
    </body>
</html>
