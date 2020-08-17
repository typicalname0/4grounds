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
        <style type="text/css">
            .left, .right {
                width: calc(50% - 10px);
            }
            .left {float: left;}
            .right {float: right;}
        </style>
    </head>
    <body> 
        <?php require(__DIR__ . "/../important/header.php"); ?>
        
        <div class="container">
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST')  {
                    if(!isset($_SESSION['user'])) {
                        $error = "you are not logged in";
                        goto skipcomment;
                    }
                    if(!$_POST['comment']) {
                        $error = "your comment cannot be blank";
                        goto skipcomment;
                    }
                    if(strlen($_POST['comment']) > 500) {
                        $error = "your comment must be shorter than 500 characters";
                        goto skipcomment;
                    }
    
                    $stmt = $conn->prepare("INSERT INTO `groupcomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                    $stmt->bind_param("iss", $_GET['id'], $_SESSION['user'], $text);
                    $text = $_POST['comment'];
                    $stmt->execute();
                    $stmt->close();
                }
                skipcomment:

                if(isset($_GET['id'])) {
                    $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows === 0) echo('Group doesnt exist.');
                    while($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        ?>
                        <img style='border: 1px solid white; width: 5em;'
                             src='/dynamic/pfp/<?php echo getPFP($row["author"], $conn);?>'>
                        <span style='float: right;text-align: right;'>
                            <a href='/view/group?id=<?php echo $row["id"];?>' style='color: gold;font-size:1.5em'>
                                <?php echo $row['title'];?>
                            </a><br>
                            <small>
                                <i>
                                    Created by
                                    <a href='/view/profile?id=<?php echo getID($row["author"], $conn);?>'>
                                        <?php echo $row['author'];?>
                                    </a>
                                </i><br>
                                <?php echo $row['date']?>
                            </small>
                        </span><br>
                        <small>
                            <?php echo $row['description']?>
                        </small>
                        <?php
                    }

                    $stmt = $conn->prepare("SELECT * FROM `users` WHERE currentgroup = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows === 0) echo('There are no users.');
                    $members = 0;
                    while($row = $result->fetch_assoc()) {
                        $members++;
                    }
                    echo "<span style='float:right;'><a href='/joingroup?id=" . $id. "'><button>Join</button></a></span>";
                } else {
                    header("Location: view/groups.php");
                }

            ?><br>
            <hr>
            <div>
                <div class="left">
                    <?php if(isset($error)) {echo "<small style='color:red'>" . $error . "</small>";}?>
                    <h2>Comments</h2>
                    <?php if (isset($_SESSION['user'])) { ?>
                        <form method="post" enctype="multipart/form-data">
                            <textarea required cols="35" placeholder="Comment" name="comment"></textarea><br>
                            <input type="submit" value="Post">
                            <small>max limit: 500 characters | supports <a href="https://www.markdownguide.org/basic-syntax">Markdown</a></small>
                        </form>
                        <hr>
                    <?php } ?>
                    <div id='comments'>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM `groupcomments` WHERE toid = ? ORDER BY id DESC");
                            $stmt->bind_param("s", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                
                            while($row = $result->fetch_assoc()) { ?>
                                <div class='commentRight' style='display: grid; grid-template-columns: 75% auto; padding:5px;'>
                                    <div class="commentText" style="word-wrap: break-word;">
                                        <small><?php echo $row['date']; ?></small>
                                        <?php echo validateMarkdown($row['text']);?>
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
                <div class="right">
                    <h2>Members (<?php echo $members;?>)</h2>
                    <?php
                        $stmt = $conn->prepare("SELECT `username`, `id` FROM `users` WHERE `currentgroup` = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        echo "<ul>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<li><a href='/view/profile?id=" . $row['id'] . "'>". $row['username'] . "</a></li>";
                        }
                        echo "</ul>";
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
