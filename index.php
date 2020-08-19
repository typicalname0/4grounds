<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/global.css">
        <link rel="stylesheet" href="../css/header.css">
        <?php
            require(__DIR__ . "/../func/func.php");
            require(__DIR__ . "/../func/conn.php"); 

            if(isset($_GET['id'])) {
                $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) echo('There are no users.');
                while($row = $result->fetch_assoc()) {
                    $author = $row['author'];
                    $id = $row['id'];
                    $date = $row['date'];
                    $extrainfo = $row['extrainfo'];
                    $title = $row['title'];
                    $type = $row['type'];
                    $status = $row['status'];
                    $filename = $row['filename'];

                    if($status != "y") {
                        die("Item is not approved yet.");
                    }
                }
                $stmt->close();
            }
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/../important/header.php"); ?>
        
        <div class="container">
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') 
                {
                    if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                    if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                    if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                    //if(!isset($_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }
                    //if($config['use_recaptcha'] && !validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                    $stmt = $conn->prepare("INSERT INTO `gamecomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                    $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                    $text = $_POST['comment'];
                    $stmt->execute();
                    $stmt->close();
                }
                skipcomment:
                if(isset($error)) {
                    echo "<span style='color: red;'><small>" . $error . "</small></span><br>";
                }

                echo "<br><img style='position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($author, $conn) . "'>
                <small>
                <a href='/view?id=" . $id . "'><span style='float:right;color: gold;'><i>" . $title . "</a></i></span><br>
                <span style='float:right;'><small><i>Posted by <a href='/view/profile?id=" . getID($author, $conn) . "'>" . $author . "</a></i></span><br>
                <span style='float:right;'>" . $date . "</small></span><br>
                <br><br>" . $extrainfo . "</small><hr>";
            ?>
            <?php 
            switch($type) {
                case "song":
                    echo '<audio controls> <source src="/dynamic/song/' . $filename . '"> </audio>';
                    break;
                case "image":
                    echo "<img style='max-width: 100%;' src='/dynamic/image/" . $filename . "'>";
                    break;
                case "midi":
                    echo "Note: It may take a few seconds for the MIDI to load.<br>";
                    echo "<a href='#' onClick=\"MIDIjs.play('/dynamic/midi/" . $filename . "');\">Play " . $title . "</a>";
                    echo "<br><a href='#' onClick='MIDIjs.stop();'>Stop MIDI Playback</a>";
                    break;
                case "chiptune":
                    // fixed - bloxxite
                    echo '<script type="text/javascript" src="/static/js/chiptune.js"></script>
                        <script type="text/javascript" src="//cdn.jsdelivr.net/gh/deskjet/chiptune2.js@master/libopenmpt.js"></script>
                        <script type="text/javascript" src="//cdn.jsdelivr.net/gh/deskjet/chiptune2.js@master/chiptune2.js"></script>';
                    echo '<a class="song" data-modurl="/dynamic/chiptune/' . $filename . '" href="#">Play ' . $title . '</a>';
                    break;
                case "video":
                    echo ' <video width="640" height="400" controls> <source src="/dynamic/video/' . $filename . '" type="video/mp4"> </video> ';
                    break;
                case "game":
                    echo '<embed src="/dynamic/game/' . $filename . '"  height="480px" width="640px"> </embed>';
                    break;
            }
            ?>
            <h2>User Submitted Comments</h2>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <textarea required cols="77" placeholder="Comment" name="comment"></textarea><br>
                <input type="submit" value="Post" <?php 
                  if ($config['use_recaptcha']) 
                    echo 'class="g-recaptcha" data-sitekey="' . $config['recaptcha_sitekey'] . '" data-callback="onSubmit"'
                ?>> <small>max limit: 500 characters | supports <a href="https://www.markdownguide.org/basic-syntax">Markdown</a></small>
            </form>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `gamecomments` WHERE toid = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <div class="commentsList">
                <?php while($row = $result->fetch_assoc()) { ?>
                <div class='commentRight' style='display: grid; grid-template-columns: auto 85%; padding:5px;'>
                    <div>
                        <a style='float: left;' href='/view/profile?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                        <br>
                        <img class='commentPictures' style='float: left;' height='80px;'width='80px;'src='/dynamic/pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                    </div>
                    <div style="word-wrap: break-word;">
                        <small><?php echo $row['date']; ?></small>
                        <?php echo htmlspecialchars($row['text']);?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <script>
            function onSubmit(token) {
                document.getElementById("submitform").submit();
            }
        </script>
    </body>
</html>
