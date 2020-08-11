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

            ?>
        </div>
    </body>
</html>