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
        <a href="/">
            <img height="80px;" src="/static/logo.png">
        </a>
    </center>
</div>
<div id="navbar" style="padding: 5px;width: 99.5%;border-top: 1px solid red;border-bottom: 1px solid red;background-color: darkred; color: white;">
    <b>
    <a href="/viewitems?type=news">News</a>
    &bull; <a href="/viewitems?type=video">Videos</a>
    &bull; <a href="/viewitems?type=chiptune">Chiptunes</a>
    &bull; <a href="/viewitems?type=midi">MIDIs</a>
    &bull; <a href="/viewitems?type=song">Songs</a>
    &bull; <a href="/viewitems?type=game">Games</a>
    &bull; <a href="/viewitems?type=image">Images</a>
    &bull; <a href="/viewitems?type=review">Reviews</a>
    &bull; <a href="/files">Files</a>
    &bull; <a href="/viewgroups">Groups</a>

    <span style="float:right;">
        <?php if(isset($_SESSION['user'])) {?>
        Logged in as 
        <a href='/?id=<?php echo(getID($_SESSION['user'], $conn));?>'>
            <?php echo($_SESSION['user']);?>
        </a>
        &bull; <a href='/home'>Manage</a>
        &bull; <a href='/logout'>Logout</a>
        <?php } else { ?>
        Not logged in &bull; <a href='/register'>Register</a> &bull; <a href='/login'>Login</a>
        <?php } ?>
    </span>
    </b>
</div>
<br>