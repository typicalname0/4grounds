<?php
require("conn.php");

return function($folder, $type, $filetypes) // PHP IS SO DUMB
{
    if(isset($_SESSION['user'])) {
        $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_dir = "../usergenerated/" . $folder . "/";
        $target_file = $target_dir . md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $fileType;
        $uploadOk = 1;
        $movedFile = 0;

        if(!in_array($fileType, $filetypes)) {
            echo 'unsupported file type. must be one of .' . join(", .", $filetypes) . '<hr>';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (file_exists($target_file)) {
                $movedFile = 1;
            } else {
                $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            }

            if ($movedFile == 1) {
                $stmt = $conn->prepare("INSERT INTO files (type, title, extrainfo, author, filename) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $type, $title, $description, $_SESSION['user'], $filename);

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