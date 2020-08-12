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
        <?php require("important/header.php"); ?>
        
        <div class="container">
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') 
                {
                    if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                    if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                    if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
    
                    $stmt = $conn->prepare("INSERT INTO `groupcomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                    $stmt->bind_param("iss", $_GET['id'], $_SESSION['user'], $text);
                    $unprocessedText = replaceBBcodes($_POST['comment']);
                    $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
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
                        echo "<img style='position: absolute;border: 1px solid white; width: 5em;' src='pfp/" . getPFP($row['author'], $conn) . "'>
                        <small>
                        <a href='viewgroup.php?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>" . $row['title'] . "</a></i></span><br>
                        <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                        <span style='float:right;'>" . $row['date'] . "</small></span><br>
                        <br><br>" . $row['description'] . "</small>";
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
                    echo "<hr>" . $members . " members are in this group.<span style='float:right;'><a href='joingroup.php?id=" . $id. "'><button>Join</button></a></span>";
                } else {
                    header("Location: viewgroups.php");
                }

            ?><br>
            <hr>
            <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
            <h2>Comment</h2>
            <form method="post" enctype="multipart/form-data">
                <textarea required cols="80" placeholder="Comment" name="comment"></textarea><br>
                <input type="submit" value="Post"> <small>max limit: 500 characters | bbcode supported</small>
            </form>
            <hr>
            <div id='comments'>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `groupcomments` WHERE toid = ? ORDER BY id DESC");
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
    </body>
</html>
