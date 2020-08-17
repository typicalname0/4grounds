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
        
        if(isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM files WHERE author = ? AND id = ?");
            $stmt->bind_param("si", $_SESSION['user'], $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows === 0) die('Item doesnt exist or item isnt made by you.');
        } else {
            header("Location: index.php");
        }

        if(@$_POST['submit']) {
            if(isset($_SESSION['user'])) {
                $stmt = $conn->prepare("UPDATE files SET title = ?, extrainfo = ? WHERE id = ?");
                $stmt->bind_param("ssi", $title, $description, $_GET['id']);
                $title = htmlspecialchars($_POST['title']);
                $description = htmlspecialchars($_POST['description']);
                $description = str_replace(PHP_EOL, "<br>", $description);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "You aren't logged in.";
            }
        }
        ?>
        
        <div class="container">
            <h1>Edit File</h1>
            <form method="post" enctype="multipart/form-data">
                <input size="69" type="text" placeholder="Updated Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Updated Description" name="description"></textarea><br><br>
                <input type="submit" value="Update Info" name="submit">
            </form>
        </div>
    </body>
</html>
