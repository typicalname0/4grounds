<?php
require("../func/conn.php");
require("../func/func.php");

function isAdmin($user, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) return "user doesn't exist";
    while($row = $result->fetch_assoc()) {
        if($row['rank'] == "Admin" || $row['rank'] == "Owner") {
            return "admin";
        } else {
            return "not admin";
        }
    }
    $stmt->close();
}
