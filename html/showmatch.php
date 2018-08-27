<?php
session_start();
$right = $_SESSION["right"];
$id = $_SESSION["id"];
if ($right > 1) {

    require "dbinfo.php";
    $time = $_GET['time'];
    $conn = dbconnect("MATCHES");
    $sql = "SELECT * FROM matches WHERE owner = 0 OR owner = ".$id;
    if ($right > 3) {
        $sql = "SELECT * FROM matches";
    }
    $result = $conn->query($sql);
    $matches = array();
    while ($row = $result->fetch_assoc()) {
        $matches[] = $row;
    }
    echo json_encode($matches);
    $conn->close();
}
?>
