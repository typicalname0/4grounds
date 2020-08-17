<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/index.css">
        <style>
            .leftHalf {
                float: left;
                width: calc(35% - 20px);
            }

            .rightHalf {
                float: right;
                width: calc(70% - 20px);
            }
        </style>
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
            <center>
                <small><a href="about">About 4Grounds!</a> &bull; <a href="qaa">Q&A</a> &bull; <a href="https://discord.gg/4YVGbND">Discord</a></small>  
            </center>

            <br>
            <div class="leftHalf">
                <div class="note" style="background-color: #202020;">
                    <h1>4Grounds</h1>
                    <a href="/newnews">Make a News Post</a><br>
                    <a href="/newreview">Make a Review Post</a><hr>
                    <a href="/upload/midi">Upload MIDI</a><br>
                    <a href="/upload/song">Upload Song</a><br>
                    <a href="/upload/game">Upload Game</a><br>
                    <a href="/upload/video">Upload Video</a><br>
                    <a href="/upload/image">Upload Image</a><br>
                    <a href="/upload/chiptune">Upload a Chiptune</a>
                </div>
                <br>
                <div class="note" style="background-color: #202020;">
                    <b>Cool Sites</b><br>
                    <small>4Grounds Buddy Sites!</small><hr>
                    <a href="https://squibble.xyz">squibble.xyz</a><br>
                    <a href="https://newgrounds.com">NewGrounds</a><br>
                </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
             
            </div>
            <div class="rightHalf">
                <div class="note">
                    <h1>Latest Images</h1>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM files WHERE type='image' AND status='y' ORDER BY id DESC LIMIT 3");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        echo "<div style='display: inline-block;' class='notegray' style='background-color: #DC143C;'>
                            <a href='/view?id=" . $row['id'] . "'><img style='width: 7.5em;height: 7.5em;' src='/dynamic/image/" . $row['filename'] . "'>
                            <br><center><b>" . htmlspecialchars($row['title']) . "</b><br><span style='color: gray;'>By " . $row['author'] . "</span></center>
                            </a>
                        </div> ";  
                    }
                    $stmt->close();

                    ?>
                </div><br>
                <div style='width: 26em;word-wrap: break-word;' class="note">
                    <h1>Latest News</h1>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM files WHERE type='news' AND status='y' ORDER BY id DESC LIMIT 1");
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
                <div style='width: 26em;word-wrap: break-word;' class="note">
                    <h1>Latest Reviews</h1>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM files WHERE type='review' AND status='y' ORDER BY id DESC LIMIT 1");
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
                <div class="note" style="width:26em;background-color: #202020;">
                    <div class="grid-container">                
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 0) echo('There are no users.');
                            while($row = $result->fetch_assoc()) {
                                $id = 1;
                                echo "<div class='item" . $id . "'><img style='height: 7em;width: 7em;' src='/dynamic/pfp/" . getPFP($row['username'], $conn) . "'><br><a href='/view/profile?id=" . $row['id'] . "'>" . $row['username'] . "</a></div>";
                                $id = $id + 1;
                            }
                            $stmt->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
