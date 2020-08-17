<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/images.css">
        <?php
            require(__DIR__ . "/../func/func.php");
            require(__DIR__ . "/../func/conn.php"); 
            $featured = array(1, 3, 12);
            $newlines = array("<br>", " <br>", "<br>" , " <br> ");
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/../important/header.php"); ?>
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
                        echo '<b><a href="/view?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</b></a> <span style="float:right;"><small>Uploader: <b>' . $row['author'] . '</b></small></span><br>
                        ' . $row['extrainfo'] . '<br>
                        <audio controls>
                        <source src="/dynamic/song/' . $row['filename'] . '">
                        </audio><hr>';
                    } else if($row['type'] == "image") {
                        //im sorry for this abomination
                        echo "<div style='display: inline-block;' class='notegray'>
                            <a href='/view?id=" . $row['id'] . "'><img style='width: 10em;height: 10em;' src='/dynamic/image/" . $row['filename'] . "'>
                            <br><center><b>" . htmlspecialchars($row['title']) . "</b><br><span style='color: gray;'>By " . $row['author'] . "</span></center>
                            </a>
                        </div> ";  
                    } else if($row['type'] == "news" || $row['type'] == "review") {
                        echo "<small>
                        <img style='position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($row['author'], $conn) . "'>
                        <a href='/view?id=" . $row['id'] . "'><span style='float:right;color: gold;'>[" . $row['agerating'] . "] <i>" . $row['title'] . "</a></i></span><br>
                        <span style='float:right;'><small><i>Posted by <a href='/view/profile?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                        <span style='float:right;'>" . $row['date'] . "</small></span><br>
                        <br><br>" . $row['extrainfo'] . "</small><hr>";
                    } else {
                        echo "<b><a href='/view?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . " by " . $row['author'] . "
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
