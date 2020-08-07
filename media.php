<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require("func/func.php");
            require("func/conn.php"); 

            if(isset($_GET['id'])) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) echo('There are no users.');
                while($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    $id = $row['id'];
                    $date = $row['date'];
                    $bio = $row['bio'];
                    $css = $row['css'];
                    $pfp = $row['pfp'];
                    $music = $row['music'];
                    echo '<style>' . $css . '</style>';
                }
                $stmt->close();
            }
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require("important/header.php"); ?>
        
        <div class="container"><br>
            <h1 style="display: inline-block;margin:0px;">Featured Music of the Day</h1>
            <audio controls>
                <source src="music/<?php echo $music; ?>">
            </audio><br>
            Uploader: <b><a href="">example</a></b><br>
            Song title: <b>"Song"</b><br>
            Extra Info: <b>Extra</b><br>
            https://blahjblhab.mp3<br><br>
            <h1 style="display: inline-block;margin:0px;">Featured Game of the Day</h1>
            <audio controls>
                <source src="music/<?php echo $music; ?>">
            </audio><br>
            Uploader: <b><a href="">example</a></b><br>
            Game title: <b>"Song"</b><br>
            Description: <b>Extra</b><br>
            https://blahjblhab.mp3
        </div>
    </body>
</html>