<!DOCTYPE html>
<html>
    <head>
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows === 0) header('Location: index.php');
            while($row = $result->fetch_assoc()) {
                $username = $row['username'];
                $id = $row['id'];
                $date = $row['date'];
                $bio = $row['bio'];
                $css = $row['css'];
                $pfp = $row['pfp'];
                $music = $row['music'];
            }
            $stmt->close();
        ?>
        <title>4Grounds - Hub</title>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); 
        
        if(@$_POST['bioset']) {
            $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE `users`.`username` = ?;");
            $stmt->bind_param("ss", $text, $_SESSION['user']);
            $text = $_POST['bio'];
            $stmt->execute(); 
            $stmt->close();
            header("Location: home.php");
        } else if(@$_POST['css']) {
            $stmt = $conn->prepare("UPDATE users SET css = ? WHERE `users`.`username` = ?;");
            $stmt->bind_param("ss", $validatedcss, $_SESSION['user']);
            $validatedcss = validateCSS($_POST['css']);
            $stmt->execute(); 
            $stmt->close();
            header("Location: home.php");
        } else if(@$_POST['submit']) {
            $target_dir = "dynamic/pfp/";
            $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
            $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $imageFileType;

            $target_file = $target_dir . $target_name;
            
            $uploadOk = true;
            $movedFile = false;

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo 'unsupported file type. must be jpg, png, jpeg, or gif<hr>';
                $uploadOk = false;
            }

            if (file_exists($target_file)) {
                $movedFile = true;
            } else {
                $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            }

            if ($uploadOk) {
                if ($movedFile) {
                    $stmt = $conn->prepare("UPDATE users SET pfp = ? WHERE `users`.`username` = ?;");
                    $stmt->bind_param("ss", $target_name, $_SESSION['user']);
                    $stmt->execute(); 
                    $stmt->close();
                } else {
                    echo 'fatal error<hr>';
                }
            }
        } else if(@$_POST['photoset']) {
            $uploadOk = true;
            $movedFile = false;

            $target_dir = "dynamic/song/";
            $songFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
            $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $songFileType;
           

            $target_file = $target_dir . $target_name;

            if($songFileType != "ogg" && $songFileType != "mp3") {
                echo 'unsupported file type. must be mp3 or ogg<hr>';
                $uploadOk = false;
            }

            if (file_exists($target_file)) {
                $movedFile = true;
            } else {
                $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            }

            if ($uploadOk) {
                if ($movedFile) {
                    $stmt = $conn->prepare("UPDATE users SET music = ? WHERE `users`.`username` = ?;");
                    $stmt->bind_param("ss", $target_name, $_SESSION['user']);
                    $stmt->execute(); 
                    $stmt->close();
                } else {
                    echo 'fatal error' . $_FILES["fileToUpload"]["error"] . '<hr>';
                }
            }
        }
        ?>
        
        <div class="container">
            <form method="post" enctype="multipart/form-data">
                <small>Select photo:</small>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>
            <form method="post" enctype="multipart/form-data">
                <small>Select song:</small>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Song" name="photoset">
            </form><br>
            <button><a href="/2fa">Manage 2-Factor Authentication</a></button><br><br>
            <b>Bio</b>
            <form method="post" enctype="multipart/form-data">
                <textarea required cols="58" placeholder="Bio" name="bio"><?php echo $bio;?></textarea><br>
                <input name="bioset" type="submit" value="Set">
                <small>max limit: 500 characters | supports <a href="https://www.markdownguide.org/basic-syntax">Markdown</a></small>
            </form><br>
            <b>CSS</b>
            <button onclick="loadpfwin()" id="prevbtn">Show Live CSS Preview</button>
            <form method="post" enctype="multipart/form-data">
                <textarea required rows="15" cols="58" placeholder="Your CSS" name="css" id="css_code"><?php echo $css;?></textarea><br>
                <input name="cssset" type="submit" value="Set"> <small>max limit: 5000 characters</small>
            </form><br>
        </div>
    </body>
</html>

<!-- CSS Editor -->
<script>
	// Constants (should be defined by PHP)
	let webroot = "https://spacemy.xyz";
	let profile_id = <?php echo getID($_SESSION['user'], $conn) ?>;

	// Global vars
	var profile_window;
	var chkclose_timer;

	function freepfwin() {
		// Enable Open Preview button
		document.getElementById("prevbtn").style.display = null;

		// Disable changes being sent to preview
		document.getElementById("css_code").onkeyup = null;
	}

	function loadpfwin() {
		profile_window = window.open( `${webroot}/view/profile?id=${profile_id}&ed`, "4gpreviewCSS", "width=920,height=600" );

		profile_window.window.onload = () => {
			// Disable Open Preview button
			document.getElementById("prevbtn").style.display = "none";

			// Get style from window
			document.getElementById("css_code").innerHTML = profile_window.document.getElementById("userCSS").innerHTML;

			// Any changes change css on preview
			document.getElementById("css_code").onkeyup = () => {
				profile_window.document.getElementById("userCSS").innerHTML = document.getElementById("css_code").value;
			};
		};

		chkclose_timer = setInterval(()=>{
			if (profile_window.closed) {
                console.log("closed")
				clearInterval(chkclose_timer);
				freepfwin();
			}
		}, 100);
	};

</script>
