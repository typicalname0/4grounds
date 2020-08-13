<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); 
        
        if(@$_POST['submit']) {
            if(isset($_SESSION['user'])) {
                $stmt = $conn->prepare("INSERT INTO files (type, title, extrainfo, author, filename) VALUES ('review', ?, ?, ?, '')");
                $stmt->bind_param("sss", $title, $description, $_SESSION['user']);

                $title = htmlspecialchars($_POST['title']);
                $description = htmlspecialchars($_POST['description']);
                $description = str_replace(PHP_EOL, "<br>", $description);

                $stmt->execute();
                $stmt->close();
            }
        }
        ?>
        
        <div class="container"><br>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `files` WHERE author = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_SESSION['user']);
                $stmt->execute();
                $result = $stmt->get_result();

                while($row = $result->fetch_assoc()) { 
                    echo "<br><img style='position: absolute;border: 1px solid white; width: 5em;' src='pfp/" . getPFP($row['author'], $conn) . "'>
                    <small>
                    <a href='view.php?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>[" . $row['agerating'] . "] " . $row['title'] . "</a></i></span><br>
                    <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                    <span style='float:right;'>" . $row['date'] . "<br>
                    <span style='float:right;'><a href='updateinfo.php?id=" . $row['id'] . "'>Edit</a></small></small></span><br>
                    <br><br>" . $row['extrainfo'] . "</small><hr>";
                }
            ?>
        </div>
    </body>
</html>
