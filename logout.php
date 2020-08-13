<?php
session_destroy();
$_SESSION = array();
setcookie("PHPSESSID", "", time() - 3600);
header("Location: index.php");
?>