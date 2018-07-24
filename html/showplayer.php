<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
    $time = $_GET['time'];
    $dbname = $_GET['dbname'];
    $team = $_GET['team'];
    $conn = dbconnect($dbname);
    $sql = "SELECT * FROM Players WHERE Team = '".$team."'";
    $result = $conn->query($sql);
    $players = array();
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
    echo json_encode($players);
    $conn->close();

}
?>
