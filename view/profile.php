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
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows !== 0){ // echo('There are no users.'); // please just refuse to give a user if this is the case
            while($row = $result->fetch_assoc()) { // you dont need to use a loop if its only ever gonna return 1 or 0
                $username = $row['username']; // you dont actually need all of these variables oh my god just use an array
                $id = $row['id'];
                $date = $row['date'];
                $currentgroup = $row['currentgroup'];
                $bio = $row['bio'];
                $css = $row['css'];
                $pfp = htmlspecialchars($row['pfp']);
                $rank = $row['rank'];
                $badges = explode(';', $row['badges']);
                $currentgroup = $row['currentgroup'];
                $music = $row['music'];
                echo '<style>' . $css . '</style>';
                echo '<meta property="og:title" content="' . $username . ' \'s 4Grounds profile" />';
                echo '<meta property="og:description" content="' . htmlspecialchars($bio) . '" />';
                echo '<meta property="og:image" content="https://spacemy.xyz/dynamic/pfp/' . $pfp . '" />';
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
        $stmt->bind_param("i", $currentgroup);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows !== 0){ //echo('There are no users.'); // why again
            while($row = $result->fetch_assoc()) {
                $grouptitle = $row['title'];
            }
        }else{
            $grouptitle = "none";
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM gamecomments WHERE author = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = 0;
        while($row = $result->fetch_assoc()) {
            $comments++;
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM comments WHERE author = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $profilecomments = 0;
        while($row = $result->fetch_assoc()) {
            $profilecomments++;
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM files WHERE author = ? AND status='y'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $filesuploaded = 0;
        while($row = $result->fetch_assoc()) {
            $filesuploaded++;
        }
        $stmt->close();


        ?>
        
        <div class="container"><br>
        <div id="groundtext"><center><h1><?php echo $username; ?>'s Ground</h1></center></div>
                <div class="leftHalf">
                    <div class="notegray">
                        <center>
                        <br>
                        <img style="border: 1px solid white; width: 15em;" src="/dynamic/pfp/<?php echo $pfp; ?>">
                        </center>
                        <hr style="border-top: 1px dashed gray;">
                        <div id="userinfo" style="padding-left: 20px;">
                            <span style="color: gold;">Rank:</span> <?php echo $rank;?><br>
                            <span style="color: gold;">ID:</span> <?php echo $id;?><br>
                            <span style="color: gold;">Other Comments:</span> <?php echo $comments;?><br>
                            <span style="color: gold;">Profile Comments:</span> <?php echo $profilecomments;?><br>
                            <span style="color: gold;">Current Group:</span> <?php echo $grouptitle;?><br>
                            <span style="color: gold;">Files Uploaded:</span> <?php echo $filesuploaded;?>
                        </div><br>
                        <?php if (!isset($_GET["ed"])) { ?>
                            <audio autoplay controls>
                                <source src="/dynamic/song/<?php echo $music; ?>">
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
                            foreach($badges as $badge) {
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
                        <?php echo validateMarkdown($bio); ?>
                    </div><br><br>
                    <div id='comments'>
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



