<?php
require("dependencies.php");

if(isset($_SESSION['user'])) {
    if(isAdmin($_SESSION['user'], $conn) == "admin") {
        echo "<h1>Welcome</h1>";
    } else {
        die("not a admin");
    }
} else {
    die("not logged in");
}

$load = sys_getloadavg();
?>
<h2>Server Status</h2>
<?php
echo $load[0] . " Load Average<br>";
echo memory_get_usage() . " bytes of RAM allocated to PHP";
?>
<h2>Items waiting for Approval</h2>

<?php
    $stmt = $conn->prepare("SELECT * FROM files WHERE status = 'n'");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) echo('There are no items waiting for approval.');
    while($row = $result->fetch_assoc()) {
        echo "" . $row['title'] . " by " . $row['author'] . " @ " . $row['date'] . " | <a href='approve.php?id=" . $row['id'] . "'>Approve</a> | <a href='deny.php?id=" . $row['id'] . "'>Deny</a><br>";
    }
?>

<h2>Users</h2>

<?php
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) echo('There are no items waiting for approval.');
    while($row = $result->fetch_assoc()) {
        echo "" . $row['username'] . " | <a href='ban.php?id=" . $row['id'] . "'>Ban</a><br>";
    }
?>