<?php
require "dbinfo.php";
$dbname = $_GET['dbname'];
$teamname = $_POST['teamname'];
$kitcolor = $_POST['kitcolor'];
$level = $_POST['level'];
$groupname = $_POST['groupname'];
$conn = dbconnect($dbname);
$sql = "INSERT INTO Teams (TeamName, KitColor, Level, GroupName) VALUES ('".$teamname."','".$kitcolor."','".$level."','".$groupname."')";
echo $sql;
$conn->query($sql);
$conn->close();
header("location:./teammanage.php?Match=".$dbname);
?>
