<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
    $dbname = $_POST['dbname'];
    #$teamname = $_POST['teamname'];
    $matchid = $_POST['matchid'];
    $hometeam = $_POST['hometeam'];
    $awayteam = $_POST['awayteam'];
    $matchtime = $_POST['matchtime'];
    $matchfield = $_POST['matchfield'];
    $level = $_POST["level"];
    $stage = $_POST["stage"];
    $groupname = $_POST["groupname"];
    $round = $_POST["round"];
    $conn = dbconnect($dbname);
    $sql = "UPDATE Matches SET HomeTeam = '".$hometeam."', AwayTeam  = '".$awayteam."' , MatchTime = '".$matchtime."', MatchField = '".$matchfield."', Level = '".$level."', Stage = '".$stage."', GroupName = '".$groupname."', Round = ".$round." WHERE MatchID = ".$matchid;
    $res = $conn->query($sql);
    echo $sql;
    $conn->close();

}
?>
