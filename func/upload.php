<?php
require(__DIR__ . "/conn.php"); 

return function($type, $allowedFileTypes) {
    if(isset($_SESSION['user'])) {
        $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_dir = __DIR__ . "/../dynamic/" . $type . "/";
        $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $fileType;
        $target_file = $target_dir . $target_name;
        $uploadOk = 1;
        $movedFile = 0;
        
    
        if (file_exists($target_file)) {
            $movedFile = true;
        } else {
            $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }

        if(!in_array($fileType, $allowedFileTypes)) {
            echo 'unsupported file type. must be one of ' . join(", ", $allowedFileTypes) . '<hr>';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if ($movedFile) {
                $stmt = $conn->prepare("INSERT INTO files (type, title, extrainfo, author, filename) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $type, $title, $description, $_SESSION['user'], $filename);
    
                $filename = htmlspecialchars($target_name);
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