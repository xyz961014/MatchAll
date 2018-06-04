<?php
function dbconnect($dbname) {
    $servername = "localhost";
    $username = "root";
    $password = "961014";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_query($conn,'set names utf8');
    if ($conn->connect_error) {
        die("Connection failed:".$conn->connect_error);
    }
    return $conn;
}
?>
