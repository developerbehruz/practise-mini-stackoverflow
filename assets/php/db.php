<?php
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "stackoverflow";

    $conn = mysqli_connect($host, $db_user, $db_password, $db_name);

    if (mysqli_error($conn)) {
        echo "Mysqli error:" . mysqli_error($conn);
    }

    function rStr($text){
        global $conn;
        return mysqli_real_escape_string($conn, $text);
    }
?>