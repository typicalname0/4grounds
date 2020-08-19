<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/global.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/index.css">
        <style>
            .leftHalf {
                float: left;
                width: calc(35% - 20px);
            }

            .rightHalf {
                float: right;
                width: calc(70% - 20px);
            }
        </style>
        <?php
            require(__DIR__ . "/func/func.php");
            require(__DIR__ . "/func/conn.php"); 


            echo '<meta property="og:title" content="Guide" />'; // PHP can suck a dick - bloxxite (f)
            echo '<meta property="og:description" content="4Grounds is an open-source newgrounds revival." />';
            echo '<meta name="twitter:card" content="https://spacemy.xyz/static/logo.png" />';
        ?>
        <title>4Grounds - Guide</title>
        <meta name="theme-color" content="#8b0000">
        <meta content="4Grounds" property="og:site_name">
    </head>
    <body> 
        <?php require(__DIR__ . "/important/header.php"); ?>
        
        <div class="container">
            <center>
                <small><a href="/">Go Back</a> &bull; Last updated <b>8/18/2020</b></small>  
            </center>
			
			<h2>Guide</h2>
			On 4Grounds, you can upload stuff like videos, MIDIs, music, images, and much more file formats to come soon. Your profiles are customizeable with CSS, and markdown is supported. You can comment on other people's profiles too. There are also comments and reviews and news, which you can use to express your thoughts on the front page of the website. <small>(Uploaded Items are manually approved)</small><br>
			<br>If you want to read more about our project, you can read more below.
			<h2>Customization</h2>
			Your profile is very customizeable in terms of looks, and you can grab the Class/ID names with inspect element. You can also customize your bio which supports markdown.<br>
        
			<h2>File Uploading</h2>
			There are a lot of types of files you can upload, like Chiptunes (XM, MOD, IT, S3M) and videos, MIDIs, Songs (MP3 & OGG), Games (SWF), Images (PNG, JPG, JPEG, GIF).<br>
			
			<h2>Security</h2>
			We do <b>NOT</b> sell your data, your passwords are encrypted with BCRYPT. Our project is open source and can be found <a href="https://github.com/typicalname0/4grounds">here</a>.<br>
			
			<h2>Misc</h2>
			You can contribute to the project by going to the GitHub repository. You can also contact me on discord. tydentloR#1390
		</div>
    </body>
</html>
