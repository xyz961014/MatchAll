<?php
require 'TeamDict.php';
require 'dbinfo.php';
$id = $_GET['MatchID'];
$dbname = $_GET['dbname'];
$eventid = $_GET['EventID'];
$conn = dbconnect($dbname);
$sql = "DELETE FROM ".$id." WHERE EventID = ".$eventid;
$res = $conn->query($sql);
$conn->close();
?>
