<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require("func/func.php");
            require("func/conn.php"); 

            if(isset($_GET['id'])) {
                getUser($_GET['id']);
                echo '<style>' . $css . '</style>';
            }
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require("important/header.php"); ?>
        
        <div class="container">
            <br>   
            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                if(!isset($_SESSION['user'])){ $error = "you are not logged in"; }
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; }
                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; }
                if (!isset($error)) {
                    $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, text) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                    $unprocessedText = replaceBBcodes($_POST['comment']);
                    $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            skipcomment:

            if(isset($_GET['id'])) {?>
                <center><h1><?php echo $username; ?>'s Ground</h1></center>
                <div class="leftHalf">
                    <div class="notegray">
                        <center>
                        <br>
                        <img style="width: 15em;" src="pfp/<?php echo $pfp; ?>"><br>
                        <audio controls>
                        <source src="music/<?php echo $music; ?>">
                        </audio> 
                        </center>
                    </div>
                    <br>
                    <div class="notegray">
                    <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
                    <h2>Comment</h2>
                    <form method="post" enctype="multipart/form-data" id="submitform">
                        <textarea required cols="33" placeholder="Comment" name="comment"></textarea><br>
                        <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin"> <small>max limit: 500 characters | bbcode supported</small>
                    </form>
                    </div> 
                    <center><br>
                    <a href="##" onclick="history.go(-1); return false;"><< back</a>
                </div>
                <div class="rightHalf">
                    <div class="notegray">
                        <h1>Badges</h1>
                        <?php
                            foreach($badges as $badge) {
                                echo "<img width='70px' height='70px' src='". $badge ."'>";
                            }
                        ?>
                    </div><br>
                    <div class="notegray">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM `files` WHERE author = ? AND status='y' ORDER BY id DESC ");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows > 0) echo('<h1>Files</h1>');
                        
                        while($row = $result->fetch_assoc()) { 
                            echo '<a href="view.php?id=' . $row['id'] . '">' . $row['title'] . ' [' , $row['type'] . ']</a><br>';
                        }?> 
                    </div><br>
                    <div class="notegray">
                        <h1>Bio</h1>
                        <?php echo $bio; ?>
                    </div><br><br>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM `comments` WHERE toid = ? ORDER BY id DESC");
                        $stmt->bind_param("s", $_GET['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        while($row = $result->fetch_assoc()) { ?>
                            <div class='commentRight' style='display: grid; grid-template-columns: 75% auto; padding:5px;'>
                                <div style="word-wrap: break-word;">
                                    <small><?php echo $row['date']; ?></small>
                                    <br>
                                    <?php echo $row['text']; ?>
                                </div>
                                <div>
                                    <a style='float: right;' href='?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                                    <br>
                                    <img class='commentPictures' style='float: right;' height='80px;'width='80px;'src='pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                                </div>
                            </div>
                    <?php } ?>
                </div>

            <?php } else { ?>
            <div style="width: 40em;" class="notegray">

            </div>
            <div class="leftHalf">
                <div class="note" style="background-color: #202020;">
                    <h1>4Grounds - Hub</h1>
                    <a href="viewitems.php?type=midi">MIDIs</a> or <a href="uploadmidi.php">Upload MIDI</a><br>
                    <a href="viewitems.php?type=song">Songs</a> or <a href="uploadmusic.php">Upload Song</a><br>
                    <a href="viewitems.php?type=game">Games</a> or <a href="uploadgame.php">Upload Game</a><br>
                    <a href="viewitems.php?type=chiptune">Chiptunes</a> or <a href="uploadchiptune.php">Upload a Chiptune</a>
                    <hr>
                    <a href="register.php">Register</a><br>
                    <a href="login.php">Login</a><br>
                    <a href="media.php">Featured</a><br>
                    <?php 
                    if(isset($_SESSION['user'])) { echo "<a href='home.php'>Manage</a>"; }
                    ?>
                </div>
                <br>
                <div class="note" style="background-color: #202020;">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM users");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows === 0) echo('There are no users.');
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='?id=" . $row['id'] . "'>" . $row['username'] . "</a><br>";
                        }
                        $stmt->close();
                    ?>
                </div>
            </div>
            <div class="rightHalf">
                <div class="note">
                    <h1>Featured Media</h1>
                    hhgregginspace PLUS! by <b>worldcash</b><br>
                    <embed src="gamefiles/hhgregginspaceplus.swf"  height="150px" width="290px"> </embed>
                </div><br>
                <div class="note">
                    <h1>News</h1>
                    Loads of money!
                </div>
            </div>
            <?php } ?>
        </div>
    </body>
</html>
