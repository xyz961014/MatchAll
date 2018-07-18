<?php
require "dbinfo.php";
$dbname = $_POST['dbname'];
#$teamname = $_POST['teamname'];
$matchid = $_POST['matchid'];
$hometeam = $_POST['hometeam'];
$awayteam = $_POST['awayteam'];
$matchtime = $_POST['matchtime'];
$matchfield = $_POST['matchfield'];
$conn = dbconnect($dbname);
$sql = "UPDATE Matches SET HomeTeam = '".$hometeam."', AwayTeam  = '".$awayteam."' , MatchTime = '".$matchtime."', MatchField = '".$matchfield."' WHERE MatchID = ".$matchid;
$res = $conn->query($sql);
echo $sql;
$conn->close();
?>
