<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); ?>
        
        <div class="container"><br>
            <h1 style="display: inline-block;margin:0px;">Featured Music of the Day</h1>

            <?php
                $stmt = $conn->prepare("SELECT * FROM files WHERE id = 27");
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    echo "<br><img style='height: 5em;position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($row['author'], $conn) . "'>
                    <small>
                    <a href='view.php?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>[" . $row['agerating'] . "] " . $row['title'] . "</a></i></span><br>
                    <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                    <span style='float:right;'>" . $row['date'] . "</small></span><br>
                    <br><br>" . $row['extrainfo'] . "</small>";
                    echo '<br>
                    <audio controls>
                        <source src="/dynamic/song/' . $row['filename'] . '">
                    </audio><br>';
                }
            ?>
            <hr>
            <h1 style="display: inline-block;margin:0px;">Featured Game of the Day</h1>
            <?php
                $stmt = $conn->prepare("SELECT * FROM files WHERE id = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    echo "<br><img style='height: 5em;position: absolute;border: 1px solid white; width: 5em;' src='/dynamic/pfp/" . getPFP($row['author'], $conn) . "'>
                    <small>
                    <a href='view.php?id=" . $row['id'] . "'><span style='float:right;color: gold;'><i>[" . $row['agerating'] . "] " . $row['title'] . "</a></i></span><br>
                    <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a></i></span><br>
                    <span style='float:right;'>" . $row['date'] . "</small></span><br>
                    <br><br>" . $row['extrainfo'] . "</small><br>";
                    echo '<embed src="/dynamic/game/' . $row['filename'] . '"  height="300px" width="500px"> </embed>';
                }
            ?>
        </div>
    </body>
</html>
