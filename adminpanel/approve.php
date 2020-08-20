<?php
require(__DIR__ . "/dependencies.php");

if(isset($_SESSION['user'])) {
    if(isset($_GET['id'])) {
        if(isAdmin($_SESSION['user'], $conn)) {
            $stmt = $conn->prepare("UPDATE files SET status = 'y' WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $stmt->close();
            
            header("Location: index.php?success=true");
        } else {
            die("not a admin");
        }
    }
} else {
    die("not logged in");
}
