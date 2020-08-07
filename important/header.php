<?php
if(isset($_SESSION['user'])) {
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 0) echo('welcome to gamestop how may i help you');
}
?>

<div class="header">
    <?php
    if(isset($_SESSION['user'])) {
        echo "Logged in as <b>" . $_SESSION['user'] . "</b>";
    } else {
        echo "Not logged in";
    }
    ?>
    <center>
    <a href="index.php"><img height="80px;" src="https://cdn.discordapp.com/attachments/740680780740821105/741174208121405510/4grunds.png"></a>
    </center>
</div>