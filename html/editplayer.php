<?php
require "dbinfo.php";
$dbname = $_POST['dbname'];
#$teamname = $_POST['teamname'];
$playerid = $_POST['playerid'];
$name = $_POST['name'];
$class = $_POST['class'];
$idnumber = $_POST['idnumber'];
$phonenumber = $_POST['phonenumber'];
$kitnumber = $_POST['kitnumber'];
$extrainfo = $_POST['extrainfo'];
$valid = $_POST['valid'];
$conn = dbconnect($dbname);
if ($idnumber != "null") {
    $idnumber = "'".$idnumber."'";
}
$sql = "UPDATE Players SET Name = '".$name."' , Class = '".$class."' , IDNumber = ".$idnumber.", PhoneNumber = '".$phonenumber."', KitNumber = ".$kitnumber.", ExtraInfo = '".$extrainfo."', Valid = ".$valid." WHERE PlayerID = ".$playerid;
$res = $conn->query($sql);
echo $sql;
$conn->close();
?>
