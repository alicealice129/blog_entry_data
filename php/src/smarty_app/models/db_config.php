<?php
    // configure the database
    $config=array(
        "servername" => "db:3306",
        // "servername" => "localhost:3306",
        "username" => "exam0144",
        "password" => "vE7NmyyTsPtlayBu",
        "dbname" => "exam0144"
    );

    $conn = NULL;

    // function to get the db connection
    function get_db_conn() {
        global $conn, $config;

        // if the connection exists, return it.
        if ($conn) {
            return $conn;
        // else create new one
        } else {
            $servername = $config['servername'];
            $username = $config['username'];
            $password = $config['password'];
            $dbname = $config['dbname'];

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            mysqli_set_charset($conn,"utf8");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            return $conn;
        }
    }
?>