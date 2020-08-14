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
        <?php require(__DIR__ . "/important/header.php"); ?>
        
        <div class="container">
            <h1 style="margin-bottom:0px;">4Grounds Groups</h1><a href="newgroup.php">New Group</a><hr>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `groups` ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) echo('There are no users.');
                while($row = $result->fetch_assoc()) {
                    echo "<img style='position: absolute;border: 1px solid white; width: 5em;' src='pfp/" . getPFP($row['author'], $conn) . "'>
                    <small>
                    <a href='viewgroup.php?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>" . $row['title'] . "</a></i></span><br>
                    <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                    <span style='float:right;'>" . $row['date'] . "</small></span><br>
                    <br><br>" . $row['description'] . "</small><hr>";
                }
            ?>
        </div>
    </body>
</html>
