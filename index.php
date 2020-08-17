<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/index.css">
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 


            echo '<meta property="og:title" content="Hub" />'; // PHP can suck a dick - bloxxite (f)
            echo '<meta property="og:description" content="4Grounds is an open-source newgrounds revival." />';
            echo '<meta name="twitter:card" content="https://spacemy.xyz/static/logo.png" />';
        ?>
        <title>4Grounds - Hub</title>
        <meta name="theme-color" content="#8b0000">
        <meta content="4Grounds" property="og:site_name">
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); ?>
        
        <div class="container">
            <br>   
            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }

                $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, text) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                $unprocessedText = replaceBBcodes($_POST['comment']);
//                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $text = $_POST['comment'];
                $stmt->execute();
                $stmt->close();
            }
            skipcomment:
            ?>
                

            <div class="leftHalf">
                <div class="note" style="background-color: #202020;">
                    <h1>4Grounds - Hub</h1>
                    <a href="https://discord.gg/4YVGbND">Join Our Discord</a><br>
                    <a href="/newnews">Make a News Post</a><br>
                    <a href="/newreview">Make a Review Post</a><hr>
                    <a href="/upload/midi">Upload MIDI</a><br>
                    <a href="/upload/song">Upload Song</a><br>
                    <a href="/upload/game">Upload Game</a><br>
                    <a href="/upload/video">Upload Video</a><br>
                    <a href="/upload/image">Upload Image</a><br>
                    <a href="/upload/chiptune">Upload a Chiptune</a>
                    <hr>

                    <a href="/media">Featured</a><br>
                    <?php 
                    if(isset($_SESSION['user'])) { echo "<a href='/home'>Manage</a><br><a href='/files'>Files</a>"; }
                    ?>
                </div>
                <br>
                <div style='width: 18em;word-wrap: break-word;' class="note">
                    <h1>Reviews</h1>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM files WHERE type='review' AND status='y' ORDER BY RAND() LIMIT 1");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {
                            echo "<br><img style='height: 5em;position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($row['author'], $conn) . "'>
                            <small>
                            <a href='/view?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>[" . $row['agerating'] . "] " . $row['title'] . "</a></i></span><br>
                            <span style='float:right;'><small><i>Posted by <a href='/view/profile?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                            <span style='float:right;'>" . $row['date'] . "</small></span><br>
                            <br><br>" . $row['extrainfo'] . "</small><hr>";
                        }
                    ?>
                </div><br>
                <div style='width: 18em;word-wrap: break-word;' class="note">
                    <h1>News</h1>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM files WHERE type='news' AND status='y' ORDER BY RAND() LIMIT 1");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {
                            echo "<br><img style='height: 5em;position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($row['author'], $conn) . "'>
                            <small>
                            <a href='/view?id=" . $row['id'] . "'><span style='float:right;color: gold;'>[" . $row['agerating'] . "] <i>" . $row['title'] . "</a></i></span><br>
                            <span style='float:right;'><small><i>Posted by <a href='/view/profile?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                            <span style='float:right;'>" . $row['date'] . "</small></span><br>
                            <br><br>" . $row['extrainfo'] . "</small><hr>";
                        }
                    ?>
                </div><br>
                <div class="note" style="width:39em;background-color: #202020;">
                    <div class="grid-container">                
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 0) echo('There are no users.');
                            while($row = $result->fetch_assoc()) {
                                $id = 1;
                                echo "<div class='item" . $id . "'><img style='height: 8em;width: 8em;' src='/dynamic/pfp/" . getPFP($row['username'], $conn) . "'><br><a href='/view/profile?id=" . $row['id'] . "'>" . $row['username'] . "</a></div>";
                                $id = $id + 1;
                            }
                            $stmt->close();
                        ?>
                    </div>
                </div>
            </div>
            <div class="rightHalf">
                <div class="note" style='background-color: #404040;'>
                    <h1>Images</h1>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM files WHERE type='image' AND status='y' ORDER BY RAND() LIMIT 6");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        echo "<div style='display: inline-block;' class='notegray'>
                            <a href='/view?id=" . $row['id'] . "'><img style='width: 7.5em;height: 7.5em;' src='/dynamic/image/" . $row['filename'] . "'>
                            <br><center><b>" . htmlspecialchars($row['title']) . "</b><br><span style='color: gray;'>By " . $row['author'] . "</span></center>
                            </a>
                        </div> ";  
                    }
                    $stmt->close();

                    ?>
                </div><br>

            </div>
        </div>
    </body>
</html>
