<?php
    require(__DIR__ . "../cfg/config.inc.php");
    
    $lasterr = error_reporting();
    error_reporting(0); // make sure to comment this line out if you're debugging

    $conn = mysqli_connect(
        $config['database_host'], 
        $config['database_user'], 
        $config['database_pass'], 
        $config['database_database']
    );

    if (!$conn) {
            $ERROR = "Can't connect to database";
            $DESC = "PHP failed to connect to the SQL database at $SERVER.<br>Error description: " . mysqli_connect_error();
            $CHOICES = array(
                "Is the database info correct in `/cfg/config.inc.php`?",
                "Did you import fourground.sql?",
                "Make sure the MySQL server is running and working properly."
            );
            require(__DIR__ . "/error.php");
    }

    error_reporting($lasterr);

