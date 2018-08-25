<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {
    require "dbinfo.php";
    $dbname = $_GET['dbname'];
    $hometeam = $_POST['hometeam'];
    $awayteam = $_POST['awayteam'];
    $level = $_POST['level'];
    $stage = $_POST['stage'];
    $groupname = $_POST['groupname'];
    $round = $_POST['round'];
    $matchtime = $_POST['matchtime'];
    $matchfield = $_POST['matchfield'];
    $matchtime = preg_replace("/T/"," ", $matchtime);
    $matchtime = $matchtime.":00";
    if (!$round)
        $round = "NULL";
    $conn = dbconnect($dbname);
    $sql = "INSERT INTO Matches (Level, Stage, GroupName, Round, MatchTime, MatchField, HomeTeam, AwayTeam, Valid) VALUES ('".$level."','".$stage."','".$groupname."',".$round.", '".$matchtime."', '".$matchfield."', '".$hometeam."', '".$awayteam."',0)";
    echo $sql;
    $conn->query($sql);
    $conn->close();
    header("location:./schedule.php?Match=".$dbname);
}
?>
