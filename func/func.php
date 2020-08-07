<script type='text/javascript' src='//www.midijs.net/lib/midi.js'></script>


<?php
require("bbcode.php");

define("DEBUG_MODE", true);
session_start();
if(DEBUG_MODE == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function validateCSS($validate) {
	$searchVal = array("<", ">", "<?php", "?>", "behavior: url", ".php", "@import", "@\import", "@/import"); 
	$replaceVal = array("", "", "", "", "", "", "", "", ""); 
	$validated = str_replace($searchVal, $replaceVal, $validate); 
    return $validated;
}

function validateCaptcha($privatekey, $response) {
	$responseData = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$response));
	return $responseData->success;
}

function requireLogin()
{
	if(!isset($_SESSION['user'])){ header("Location: /login.php"); die(); }
}

function getID($user, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$id = $row['id'];
	} 
	$stmt->close();
	return $id;
}

function getName($id, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$name = htmlspecialchars($row['username']);
	} 
	$stmt->close();
	return $name;
}

function getPFP($user, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$pfp = htmlspecialchars($row['pfp']);
	} 
	$stmt->close();
	return $pfp;
}

function checkIfFriended($friend1, $friend2, $connection)
{
	$stmt = $connection->prepare("SELECT * FROM `friends` WHERE reciever = ? AND sender = ? OR reciever = ? AND sender = ?");
	$stmt->bind_param("ssss", $friend1, $friend2, $friend2, $friend1);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 1){ return true; }
	return false;
}

//thanks dzhaugasharov https://gist.github.com/afsalrahim/bc8caf497a4b54c5d75d
function replaceBBcodes($text) {
	return bbcode_to_html($text);
}
?>