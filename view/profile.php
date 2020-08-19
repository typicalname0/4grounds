<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
        <?php
            require(__DIR__ . "/../func/func.php");
            require(__DIR__ . "/../func/conn.php");

            $user = getUser($_GET['id'], $conn);
            echo '<style id="userCSS">' . $user['css'] . '</style>';
            echo '<meta property="og:title" content="' . $user['username'] . '\'s 4Grounds profile" />';
            echo '<meta property="og:description" content="' . htmlspecialchars($user['bio']) . '" />';
            echo '<meta property="og:image" content="https://spacemy.xyz/dynamic/pfp/' . $user['pfp'] . '" />';
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/../important/header.php"); 
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
            if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
            if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }

            $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, text) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
//                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
            $text = $_POST['comment'];
            $stmt->execute();
            $stmt->close();
        }
        skipcomment:
        ?>
        <div class="container"><br>
            <div id="groundtext">
                <center>
                    <h1><?php echo $user['username']; ?>'s Ground</h1>
                </center>
            </div>
            <div class="leftHalf">
                <div class="notegray">
                    <center>
                        <br>
                        <img style="border: 1px solid white; width: 15em;" src="/dynamic/pfp/<?php echo $user['pfp']; ?>">
                    </center>
                    <hr style="border-top: 1px dashed gray;">
                    <div id="userinfo" style="padding-left: 20px;">
                        <span style="color: gold;">Rank:</span> <?php echo $user['rank'];?><br>
                        <span style="color: gold;">ID:</span> <?php echo $user['id'];?><br>
                        <span style="color: gold;">Other Comments:</span> <?php echo $user['comments'];?><br>
                        <span style="color: gold;">Profile Comments:</span> <?php echo $user['profilecomments'];?><br>
                        <?php $userGroup = getGroup($user['currentgroup'], $conn);?>
                        <span style="color: gold;">Current Group:</span> <a href="/view/group?id=<?php echo $userGroup['id'];?>"><?php echo $userGroup['title'];?></a><br>
                        <span style="color: gold;">Files Uploaded:</span> <?php echo $user['filesuploaded'];?>
                    </div><br>
                        <?php if (!isset($_GET["ed"])) { ?>
                            <audio autoplay controls>
                                <source src="/dynamic/song/<?php echo $user['music']; ?>">
                            </audio> 
                        <?php } ?>
                    </div>
                    <br>
                    <div class="notegray">
                    <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
                    <h2>Comment</h2>
                    <form method="post" enctype="multipart/form-data">
                        <textarea required cols="33" placeholder="Comment" name="comment"></textarea><br>
                        <input type="submit" value="Post"> <small>max limit: 500 characters | supports <a href="https://www.markdownguide.org/basic-syntax">Markdown</a></small>
                    </form>
                    </div> 
                    <center><br>
                    <a href="##" onclick="history.go(-1); return false;"><< back</a>
                </div>
            <script>
           function goBack() {
                window.history.back();
                      }
                </script>
                <div class="rightHalf">
                    <div id="badges" class="notegray">
                        <h1>Badges</h1>
                        <?php
                            foreach($user['badges'] as $badge) {
                                if($badge == "good") {
                                    echo "<img width='70px;' height='70px;' src='https://cdn.discordapp.com/attachments/740680780740821105/740776214523936808/340juojg3h.png'>";
                                }
                            }
                        ?>
                    </div><br>
                    <div id="files" class="notegray">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM `files` WHERE author = ? AND status='y' ORDER BY id DESC ");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows > 0) echo('<h1>Files</h1>');
                        
                        while($row = $result->fetch_assoc()) { 
                            echo '<a href="/view?id=' . $row['id'] . '">' . $row['title'] . ' [' , $row['type'] . ']</a><br>';
                        }?> 
                    </div><br>
                    <div id="bio" class="notegray">
                        <h1>Bio</h1>
                        <?php echo validateMarkdown($user['bio']); ?>
                    </div><br><br>
                    <div id='comments'>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM `comments` WHERE toid = ? ORDER BY id DESC");
                            $stmt->bind_param("s", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            while($row = $result->fetch_assoc()) { ?>
                                <div class='commentRight' style='display: grid; grid-template-columns: 75% auto; padding:5px;'>
                                    <div class="commentText" style="word-wrap: break-word;">
                                        <small><?php echo $row['date']; ?></small>
                                        <br>
                                        <?php echo validateMarkdown($row['text']); ?>
                                    </div>
                                    <div>
                                        <a style='float: right;' href='/view/profile?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                                        <br>
                                        <img class='commentPictures' style='float: right;' height='80px;'width='80px;'src='/dynamic/pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                </div>
        </div>
    </body>
</html>



