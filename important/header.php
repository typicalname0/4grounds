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
        <a href="index.php">
            <img height="80px;" src="/static/logo.png">
        </a>
    </center>
</div>
<div id="navbar" style="padding: 5px;width: 99.5%;border-top: 1px solid red;border-bottom: 1px solid red;background-color: darkred; color: white;">
    <b>
    <a href="viewitems.php?type=news">News</a>
    &bull; <a href="viewitems.php?type=video">Videos</a>
    &bull; <a href="viewitems.php?type=chiptune">Chiptunes</a>
    &bull; <a href="viewitems.php?type=midi">MIDIs</a>
    &bull; <a href="viewitems.php?type=song">Songs</a>
    &bull; <a href="viewitems.php?type=game">Games</a>
    &bull; <a href="viewitems.php?type=image">Images</a>
    &bull; <a href="viewitems.php?type=review">Reviews</a>
    &bull; <a href="files.php">Files</a>
    &bull; <a href="viewgroups.php">Groups</a>

    <span style="float:right;">
        <?php if(isset($_SESSION['user'])) {?>
        Logged in as 
        <a href='/index.php?id=<?php echo(getID($_SESSION['user'], $conn));?>'>
            <?php echo($_SESSION['user']);?>
        </a>
        &bull; <a href='/home.php'>Manage</a>
        &bull; <a href='/logout.php'>Logout</a>
        <?php } else { ?>
        Not logged in &bull; <a href='register.php'>Register</a> &bull; <a href='login.php'>Login</a>
        <?php } ?>
    </span>
    </b>
</div>
<br>