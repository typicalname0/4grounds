<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 

            if(isset($_GET['id'])) {
                $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) echo('There are no users.');
                while($row = $result->fetch_assoc()) {
                    $author = $row['author'];
                    $id = $row['id'];
                    $date = $row['date'];
                    $extrainfo = $row['extrainfo'];
                    $title = $row['title'];
                    $type = $row['type'];
                    $status = $row['status'];
                    $filename = $row['filename'];

                    if($status != "y") {
                        die("Item is not approved yet.");
                    }
                }
                $stmt->close();
            }
        ?>
        <title>4Grounds - Hub</title>
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); ?>
        
        <div class="container">
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') 
                {
                    if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                    if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                    if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }

                    $stmt = $conn->prepare("INSERT INTO `gamecomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                    $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                    $unprocessedText = replaceBBcodes($_POST['comment']);
//                    $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                    $text = $_POST['comment'];
                    $stmt->execute();
                    $stmt->close();
                }
                skipcomment:
                if(isset($error)) {
                    echo "<span style='color: red;'><small>" . $error . "</small></span><br>";
                }

                echo "<br><img style='position: absolute;border: 1px solid white; width: 5em;' src='pfp/" . getPFP($author, $conn) . "'>
                <small>
                <a href='view.php?id=" . $id . "'><span style='float:right;color: gold;'><i>" . $title . "</a></i></span><br>
                <span style='float:right;'><small><i>Posted by <a href='index.php?id=" . getID($author, $conn) . "'>" . $author . "</a></i></span><br>
                <span style='float:right;'>" . $date . "</small></span><br>
                <br><br>" . $extrainfo . "</small><hr>";
            ?>
            <?php 
            if($type == "song") {
                echo '<audio controls>
                <source src="musicfiles/' . $filename . '">
                </audio>';
            } else if($type == "image") {
              echo "<img style='width: 10em;height: 10em;' src='images/" . $filename . "'>";
            } else if($type == "midi") {
                echo "Note: It may take a few seconds for the MIDI to load.<br>";
                echo "<a href='#' onClick=\"MIDIjs.play('midis/" . $filename . "');\">Play " . $title . "</a>";
                echo "<br><a href='#' onClick='MIDIjs.stop();'>Stop MIDI Playback</a>";
            } else if($type == "chiptune") {
                //the way i did this absolutely sucks and im
                echo '<script type="text/javascript">
                window["libopenmpt"] = {};
                libopenmpt.locateFile = function (filename) {
                  return "//cdn.jsdelivr.net/gh/deskjet/chiptune2.js@master/" + filename;
                };
                libopenmpt.onRuntimeInitialized = function () {
                  var player;
            
                  function init() {
                    if (player == undefined) {
                      player = new ChiptuneJsPlayer(new ChiptuneJsConfig(-1));
                    }
                    else {
                      player.stop();
                      playPauseButton();
                    }
                  }
            
                  function setMetadata(filename) {
                    var metadata = player.metadata();
                    if (metadata["title"] != "") {
                      document.getElementById("title").innerHTML = metadata["title"];
                    }
                    else {
                      document.getElementById("title").innerHTML = filename;
                    }
            
                    if (metadata["artist"] != "") {
                      document.getElementById("artist").innerHTML = "<br />" + metadata["artist"];
                    }
                    else {
                      document.getElementById("artist").innerHTML = "";
                    }
                  }
            
                  function afterLoad(path, buffer) {
                    document.querySelectorAll("#pitch,#tempo").forEach(e => e.value = 1);
                    player.play(buffer);
                    setMetadata(path);
                    pausePauseButton();
                  }
            
                  function loadURL(path) {
                    init();
                    player.load(path, afterLoad.bind(this, path));
                  }
            
                  function pauseButton() {
                    player.togglePause();
                    switchPauseButton();
                  }
            
                  function switchPauseButton() {
                    var button = document.getElementById("pause")
                    if (button) {
                      button.id = "play_tmp";
                    }
                    button = document.getElementById("play")
                    if (button) {
                      button.id = "pause";
                    }
                    button = document.getElementById("play_tmp")
                    if (button) {
                      button.id = "play";
                    }
                  }
            
                  function playPauseButton() {
                    var button = document.getElementById("pause")
                    if (button) {
                      button.id = "play";
                    }
                  }
            
                  function pausePauseButton() {
                    var button = document.getElementById("play")
                    if (button) {
                      button.id = "pause";
                    }
                  }
            
                  var fileaccess = document.querySelector("*");
                  fileaccess.ondrop = function (e) {
                    e.preventDefault();
                    var file = e.dataTransfer.files[0];
                    init();
            
                    player.load(file, afterLoad.bind(this, path));
                  }
            
                  fileaccess.ondragenter = function (e) { e.preventDefault(); }
                  fileaccess.ondragover = function (e) { e.preventDefault(); }
            
                  document.querySelectorAll(".song").forEach(function (e) {
                    e.addEventListener("click", function (evt) {
                      modurl = evt.target.getAttribute("data-modurl");
                      loadURL(modurl);
                    }, false);
                  });
            
                  document.querySelector("input[name=files]").addEventListener("change", function (evt) {
                    loadURL(evt.target.files[0]);
                  });
            
                  document.querySelector("input[name=submiturl]").addEventListener("click", function () {
                    var exturl = document.querySelector("input[name=exturl]");
                    modurl = exturl.value;
                    loadURL(modurl);
                    exturl.value = null;
                  });
            
                  document.querySelector("#play").addEventListener("click", pauseButton, false);
            
                  document.querySelector("#pitch").addEventListener("input", function (e) {
                    player.module_ctl_set("play.pitch_factor", e.target.value.toString());
                  }, false);
            
                  document.querySelector("#tempo").addEventListener("input", function (e) {
                    player.module_ctl_set("play.tempo_factor", e.target.value.toString());
                  }, false);
                };
            </script>
            <script type="text/javascript" src="//cdn.jsdelivr.net/gh/deskjet/chiptune2.js@master/libopenmpt.js"></script>
            <script type="text/javascript" src="//cdn.jsdelivr.net/gh/deskjet/chiptune2.js@master/chiptune2.js"></script>';
            echo '<a class="song" data-modurl="midis/' . $filename . '" href="#">Play ' . $title . '</a>';
            } else if($type == "news" || $type == "review") {
              //do nothing
            } else if($type == "video") {
              echo ' <video width="640" height="400" controls>
                  <source src="videos/' . $filename . '" type="video/mp4">
                </video> ';
            } else {
                echo '<embed src="gamefiles/' . $filename . '"  height="300px" width="500px"> </embed>';
            }
            ?>
            <h2>User Submitted Comments</h2>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <textarea required cols="77" placeholder="Comment" name="comment"></textarea><br>
                <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin"> <small>max limit: 500 characters | bbcode supported</small>
            </form>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `gamecomments` WHERE toid = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <div class="commentsList">
                <?php while($row = $result->fetch_assoc()) { ?>
                <div class='commentRight' style='display: grid; grid-template-columns: auto 85%; padding:5px;'>
                    <div>
                        <a style='float: left;' href='/index.php?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                        <br>
                        <img class='commentPictures' style='float: left;' height='80px;'width='80px;'src='/pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                    </div>
                    <div style="word-wrap: break-word;">
                        <small><?php echo $row['date']; ?></small>
                        <?php echo validateMarkdown($row['text']);?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
