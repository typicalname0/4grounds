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
        <?php require(__DIR__ . "/important/header.php"); 
        
        if(@$_POST['submit']) {
            if(isset($_SESSION['user'])) {
                $stmt = $conn->prepare("INSERT INTO files (type, title, extrainfo, author, filename) VALUES ('news', ?, ?, ?, '')");
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
            <form method="post" enctype="multipart/form-data">
                <input size="69" type="text" placeholder="News Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your news" name="description"></textarea><br><br>
                <input type="submit" value="Submit" name="submit">  <small>Note: News Posts are manually approved.</small>
            </form>
        </div>
    </body>
</html>
