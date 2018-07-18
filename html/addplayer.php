<?php
require "dbinfo.php";
$dbname = $_GET['dbname'];
$team = $_GET['team'];
$name = $_POST['name'];
$class = $_POST['class'];
$idnumber = $_POST['idnumber'];
$phonenumber = $_POST['phonenumber'];
$kitnumber = $_POST['kitnumber'];
$extrainfo = $_POST['extrainfo'];
$valid = $_POST['valid'];
if (!$idnumber)
    $idnumber = "NULL";
else
    $idnumber = "'".$idnumber."'";
$conn = dbconnect($dbname);
$sql = "INSERT INTO Players (Team, Name, Class, IDNumber, PhoneNumber, KitNumber, ExtraInfo, Valid) VALUES ('".$team."','".$name."','".$class."',".$idnumber.", ".$phonenumber.", ".$kitnumber.", '".$extrainfo."', ".$valid.")";
echo $sql;
$conn->query($sql);
$conn->close();
header("location:./playermanage.php?Match=".$dbname."&team=".$team);
?>
