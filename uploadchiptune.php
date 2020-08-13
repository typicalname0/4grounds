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
                $target_dir = "midis/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                if (file_exists($target_file)) {
                    echo 'file with the same name already exists<hr>';
                    $uploadOk = 0;
                }
                if($imageFileType != "xm" && $imageFileType != "mod" && $imageFileType != "s3m" && $imageFileType != "it") {
                    echo 'unsupported file type. must be .xm, .mod, .s3m, or .it<hr>';
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) { } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $stmt = $conn->prepare("INSERT INTO files (type, title, extrainfo, author, filename) VALUES ('chiptune', ?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $title, $description, $_SESSION['user'], $filename);

                        $filename = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
                        $title = htmlspecialchars($_POST['title']);
                        $description = htmlspecialchars($_POST['description']);
                        $description = str_replace(PHP_EOL, "<br>", $description);

                        $stmt->execute();
                        $stmt->close();
                    } else {
                        echo 'fatal error<hr>';
                    }
                }
            } else {
                echo "You aren't logged in.";
            }
        }
        ?>
        
        <div class="container"><br>
            <form method="post" enctype="multipart/form-data">
				<small>Select a Chiptune file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <hr>
                <input size="69" type="text" placeholder="Song Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your song" name="description"></textarea><br><br>
                <input type="submit" value="Upload Chiptune" name="submit">  <small>Note: Songs are manually approved.</small>
            </form>
        </div>
    </body>
</html>
