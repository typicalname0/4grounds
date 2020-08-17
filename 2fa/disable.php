<!DOCTYPE html>
<html>
    <head>
        <?php
            require(__DIR__ . "/../func/conn.php");
            require(__DIR__ . "/../func/func.php");
            requireLogin();
            // check 2fa status
            $stmt = $conn->prepare("SELECT `otpsecret`, `otpbackupcode` FROM `users` WHERE `username` = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if (!isset($result['otpsecret'])) {
                header("Location: /2fa"); die();
            }

            if (isset($_POST['unset2fa'])) {
                $temptotp = OTPHP\TOTP::create($result['otpsecret']);
                if ($temptotp->verify($_POST["unset2fa"]) || $_POST['unset2fa'] === $result['otpbackupcode']) {
                    $stmt = $conn->prepare("UPDATE `users` SET `otpsecret` = NULL, `otpbackupcode` = NULL WHERE `username` = ?");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    header("Location: /2fa"); die();
                } else {
                    $err = "Invalid code. Please try again.";
                }
            }
        ?>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
    </head>
    <body>
        <?php require(__DIR__ . "/../important/header.php"); ?>
        <div class="container">
             To disable 2FA, please type the 6-digit code your app generates or a backup code below and click 'Submit'.<br><br>
             <form method="post" enctype="multipart/form-data">
                 <input type="text" name="unset2fa" id="unset2fa"><br>
                 <input name="submit" type="submit" value="Submit">
             </form>
        </div>
    </body>
</html>
