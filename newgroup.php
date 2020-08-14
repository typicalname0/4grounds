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
        <?php require("important/header.php"); 
        
        if(@$_POST['submit']) {
            if(isset($_SESSION['user'])) {
                $stmt = $conn->prepare("INSERT INTO `groups` (title, description, author) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $text, $_SESSION['user']);
                $text = htmlspecialchars($_POST['description']);
                $text = str_replace(PHP_EOL, "<br>", $text);
                $name = htmlspecialchars($_POST['title']);
                $stmt->execute();
                $stmt->close();        
                
                $stmt = $conn->prepare("UPDATE users SET currentgroup = ? WHERE username = ?");
                $stmt->bind_param("is", $newgroupid, $_SESSION['user']);
                $newgroupid = $conn->insert_id;
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