<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
    $time = $_GET['time'];
    $dbname = $_GET['dbname'];
    $conn = dbconnect($dbname);
    $sql = "SELECT * FROM Teams";
    $result = $conn->query($sql);
    $teams = array();
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
    echo json_encode($teams);
    $conn->close();

}
?>
