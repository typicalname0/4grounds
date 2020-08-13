<?php
require(__DIR__ . "/func/func.php");
require(__DIR__ . "/func/conn.php"); 

if(isset($_SESSION['user'])) {
    if(isset($_GET['id'])) {
        $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            die("Group doesn't exist");
        }
        $stmt->close();
    
        $stmt = $conn->prepare("UPDATE users SET currentgroup = ? WHERE username = ?");
        $stmt->bind_param("is", $_GET['id'], $_SESSION['user']);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
    } else {
        header("Location: viewgroups.php");
    }
} else {
    die("you're not loged in");
}

?>