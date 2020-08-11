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
    <br>
    <center>
    <a href="index.php"><img height="80px;" src="https://cdn.discordapp.com/attachments/740680780740821105/741174208121405510/4grunds.png"></a>
    </center>
</div>
<div id="navbar" style="padding: 5px;width: 99.5%;border-top: 1px solid red;border-bottom: 1px solid red;background-color: darkred; color: white;">
    <b>
    <?php
    if(isset($_SESSION['user'])) {
        echo "Logged in as <a href='/index.php?id=" . getID($_SESSION['user'], $conn) . "'><b>" . $_SESSION['user'] . "</b></a>";
    } else {
        echo "Not logged in";
    }
    ?>
    &bull; <a href="viewitems.php?type=news">News</a> &bull; <a href="viewitems.php?type=video">Videos</a> &bull; <a href="viewitems.php?type=chiptune">Chiptunes</a>
     &bull; <a href="viewitems.php?type=midi">MIDIs</a> &bull; <a href="viewitems.php?type=song">Songs</a> &bull; <a href="viewitems.php?type=game">Games</a> &bull; <a href="viewitems.php?type=image">Images</a>
     &bull; <a href="viewitems.php?type=review">Reviews</a> &bull; <a href="files.php">Files</a>

    <span style="float:right"> <a href="register.php">Register</a> &bull; <a href="login.php">Login</a></span>
    </b>
</div>
<br>