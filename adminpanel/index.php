<?php
require("dependencies.php");

if(isset($_SESSION['user'])) {
    if(isAdmin($_SESSION['user'], $conn)) {
        echo "<h1>Welcome</h1>";
    } else {
        die("not a admin");
    }
} else {
    die("not logged in");
}

if (function_exists("sys_getloadavg")) {
    $load = sys_getloadavg();
} else {
    // PHP on windows doesn't have the sys_getloadavg() function
    $load = false;
}
?>
<h2>Server Status</h2>
<?php
if ($load) {
    echo $load[0] . " load average over the last minute<br>";
    echo $load[1] . " load average over the last 5 minutes<br>";
    echo $load[2] . " load average over the last 15 minutes<br>";
}
echo memory_get_usage() . " bytes of RAM allocated to PHP";
?>
<h2>Items waiting for Approval</h2>

<?php
    $stmt = $conn->prepare("SELECT * FROM files WHERE status = 'n'");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) echo('There are no items waiting for approval.');
    while($row = $result->fetch_assoc()) {
        echo "<a href='/view/?id=" . $row['id'] . "'>" . $row['title'] . "</a> by <a href='/view/profile?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a> @ " . $row['date'] . " | <a href='approve.php?id=" . $row['id'] . "'>Approve</a> | <a href='deny.php?id=" . $row['id'] . "'>Deny</a><br>";
    }
?>

<h2>Users</h2>

<?php
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) echo('There are no items waiting for approval.');
    while($row = $result->fetch_assoc()) {
        echo "<a href='/view/profile?id=" . $row['id'] . "'>" . $row['username'] . "</a> | <a href='ban.php?id=" . $row['id'] . "'>Ban</a><br>";
    }
?>