<!DOCTYPE html>
<html>
    <head>
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 
        ?>
        <title>4Grounds - Hub</title>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); ?>
        <center><h1 style="display: inline-block;">4Grounds - Login</h1><br>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["r_login"])) {
                    $error = "The page you tried to access requires you to be logged in.";
                }
                if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
                    if(isset($_POST['remember'])) {
                        $rememberMe = true;
                    } else {
                        $rememberMe = false;
                    }
                    $stmt = $conn->prepare("SELECT password FROM `users` WHERE username=?");
                    $stmt->bind_param("s", $_POST['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if(!mysqli_num_rows($result)){ { $error = "incorrect username or password"; goto skip; } }
                    
                    $row = $result->fetch_assoc();
                    $hash = $row['password'];
                    
                    if(!password_verify($_POST['password'], $hash)) {
                        $error = "incorrect username or password"; goto skip;
                    }

                    $stmt = $conn->prepare("SELECT `otpsecret`, `otpbackupcode` FROM `users` WHERE `username` = ?");
                    $stmt->bind_param("s", $_POST['username']);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc();
                    if (isset($result['otpsecret'])) {
                        $otp = true;
                        if (isset($_POST['totp'])) {
                            if ($_POST['totp'] === $result['otpbackupcode']) {goto skip2fa;}
                            require("vendor/autoload.php");
                            $totp = OTPHP\TOTP::create($result['otpsecret']);
                            if (!$totp->verify($_POST['totp'])) {
                                $error = "Invalid 2FA code. Please try again.";
                                goto skip;
                            }
                        } else {
                            $error = "Enter the 2FA code displayed by your authenticator app, or a backup code below.";
                            goto skip;
                        }
                    }
                    skip2fa:

                    if($rememberMe == true) {
                        session_write_close();
                        session_set_cookie_params("2678400");
                        ini_set('session.gc_maxlifetime', 2678400);
                        ini_set('session.cookie_lifetime', 2678400);
                        session_start();
                        $_SESSION['user'] = htmlspecialchars($_POST["username"]);
                    } else {
                        $_SESSION['user'] = htmlspecialchars($_POST["username"]);
                    }
                    header("Location: home.php");
                } 
                skip:

                if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } 
            ?>
            <form method="post">
                <input required placeholder="Username" type="text" name="username"><br>
                <input required placeholder="Password" type="password" name="password"><br>
                <?php if (isset($otp) && $otp) { ?>
                <input required placeholder="2FA code" type="text" name="totp"><br><br>
                <?php } ?>
                <input type="checkbox" name="remember"> Remember me<br><br>
                <input type="submit" value="Login">
            </form>
            <a href="index.php"><&lt; Back</a>
        </center>
    </body>
</html>
