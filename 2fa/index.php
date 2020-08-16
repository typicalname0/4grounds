<!DOCTYPE html>
<html>
    <head>
        <?php
            require(__DIR__ . "/../func/conn.php");
            require(__DIR__ . "/../func/func.php");
            requireLogin();

            $stmt = $conn->prepare("SELECT `otpsecret`, `otpbackupcode` FROM `users` WHERE `username` = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();

            $otpstatus = isset($result->fetch_assoc()['otpsecret']);
        ?>
        <title>4Grounds - Manage 2FA</title>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
    </head>
    <body>
        <?php require(__DIR__ . "/../important/header.php"); ?>
        <div class="container">
             <h1>2-Factor Authentication</h1>
             2FA status: <?php if ($otpstatus) {echo "enabled";} else {echo "disabled";}?><br>
             <?php if ($otpstatus) {echo "Backup code: " . $result->fetch_assoc()['otpbackupcode'];} ?><br><br>
             <button>
             <?php if ($otpstatus) { ?>
             <a href="/2fa/disable">Disable 2FA</a>
             <?php } else { ?>
             <a href="/2fa/enable">Enable 2FA</a>
             <?php } ?>
             </button>
        </div>
    </body>
</html>
