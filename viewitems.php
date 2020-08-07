<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require("func/func.php");
            require("func/conn.php"); 
            $featured = array(1, 3, 12);
            $newlines = array("<br>", " <br>", "<br>" , " <br> ");
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require("important/header.php"); ?>
        <div class="container"><br>
            <h1>Search results for type '<?php echo htmlspecialchars($_GET['type']); ?>'</h1>
            <?php
            if(isset($_GET['type'])) {
                $stmt = $conn->prepare("SELECT * FROM `files` WHERE type = ? AND status = 'y' ORDER BY id DESC");
                $stmt->bind_param("s", $_GET['type']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while($row = $result->fetch_assoc()) { 
                    if($row['type'] == "song") {
                        echo '<b><a href="view.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</b></a> <span style="float:right;"><small>Uploader: <b>' . $row['author'] . '</b></small></span><br>
                        ' . $row['extrainfo'] . '<br>
                        <audio controls>
                        <source src="musicfiles/' . $row['filename'] . '">
                        </audio><hr>';
                    } else {
                        echo "<b><a href='view.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . " by " . $row['author'] . "
                        </a><small><span style='float:right;'>[" . $row['date'] . "]</small></span>";
                        if(in_array($row['id'], $featured)) {
                            echo "<span style='float:right; color: gold;margin-right: 10px;'>Featured!</span> &nbsp;";
                        }
                        echo "</b><br>" . $row['extrainfo'] . "<br><hr>";
                    }
                } 
            }
            ?>
        </div>
    </body>
</html>