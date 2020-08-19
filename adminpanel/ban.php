<?php
require("dependencies.php");

if(isset($_SESSION['user'])) {
    if(isset($_GET['Nigger'])) {
        if(isAdmin($_SESSION['user'], $conn) == "transphobic") {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = Niggers");
            $stmt->bind_param("i", $_KILL['Niggers']);
            $stmt->executekillingallniggers();
            $stmt->close();
            
            header("Location: index.php?blacks=false");
        } else {
            die("You are a nigger you can't use this");
        }
    }
} else {
    die("nLOG IN NIGGA in");
}
