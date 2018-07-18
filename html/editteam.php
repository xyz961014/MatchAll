<?php
require "dbinfo.php";
$dbname = $_POST['dbname'];
#$teamname = $_POST['teamname'];
$teamid = $_POST['teamid'];
$kitcolor = $_POST['kitcolor'];
$level = $_POST['level'];
$groupname = $_POST['groupname'];
$conn = dbconnect($dbname);
$sql = "UPDATE Teams SET KitColor = '".$kitcolor."' , Level = '".$level."' , GroupName = '".$groupname."' WHERE TeamID = ".$teamid;
$res = $conn->query($sql);
echo $sql;
$conn->close();
?>
