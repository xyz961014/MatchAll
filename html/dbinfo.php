<?php
function dbconnect($dbname) {
    $servername = "localhost";
    $username = "root";
    $password = "961014";
    if ($dbname)
        $conn = new mysqli($servername, $username, $password, $dbname);
    else
        $conn = new mysqli($servername, $username, $password);
    mysqli_query($conn,'set names utf8');
    if ($conn->connect_error) {
        die("Connection failed:".$conn->connect_error);
    }
    return $conn;
}
?>
