<!DOCTYPE html>
<html>
    <head>
        <?php
            require(__DIR__ . "/../func/conn.php");
            require(__DIR__ . "/../func/func.php");
            requireLogin();
            // check 2fa status
            $stmt = $conn->prepare("SELECT `otpsecret` FROM `users` WHERE `username` = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();
            if (isset($result->fetch_assoc()['otpsecret'])) {
                header("Location: /2fa"); die();
            }

            if (isset($_POST["set2fa"])) {
                $temptotp = OTPHP\TOTP::create($_SESSION['otpsecret']);
                if ($temptotp->verify($_POST["set2fa"])) {
                    $stmt = $conn->prepare("UPDATE `users` SET `otpsecret` = ?, `otpbackupcode` = ? WHERE `username` = ?");
                    $backupcode = trim(ParagonIE\ConstantTime\Base32::encodeUpper(random_bytes(8)), '=');
                    $stmt->bind_param("sss", $_SESSION['otpsecret'], $backupcode, $_SESSION['user']);
                    $stmt->execute();
                    unset($_SESSION['otpsecret']);
                    header("Location: /2fa"); die();
                } else {
                    $err = "Invalid code. Please try again.";
                }
            }
            if (isset($_SESSION['otpsecret'])) {
                $secret = $_SESSION['otpsecret'];
            } else {
                $secret = trim(ParagonIE\ConstantTime\Base32::encodeUpper(random_bytes(12)), '=');
            }
            $totp = OTPHP\TOTP::create($secret);
            $totp->setLabel('4grounds');
            $totp->setIssuer('4grounds');
            $_SESSION['otpsecret'] = $secret;
        ?>
        <link rel="stylesheet" href="/static/css/global.css">
        <link rel="stylesheet" href="/static/css/header.css">
    </head>
    <body>
        <?php require(__DIR__ . "/../important/header.php"); ?>
        <div class="container">
            <?php if (isset($err)) {echo "<b style='color:red;'>" . $err . "</b><br><br>";}?>
            To enable 2FA, scan the following QR code with your authenticator app:<br><br>
            <img src="<?php echo $totp->getQrCodeUri(
                'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M&qzone=1&format=png',
                '[DATA]'
            );?>"><br><br>
            Or use the following string: <b><?php echo $secret; ?></b><br>
            Then type the 6-digit code your app generates below and click 'Submit':<br><br>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="set2fa" id="set2fa"><br>
                <input name="submit" type="submit" value="Submit">
            </form>
        </div>
    </body>
</html>
