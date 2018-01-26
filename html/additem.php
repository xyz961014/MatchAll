<?php
require 'TeamDict.php';
$id = $_GET['MatchID'];
$team = $_GET['Team'];
$kitnum = $_GET['KitNumber'];
$name = $_GET['Name'];
$type = $_GET['Type'];
$time = $_GET['Time'];
$stptime = $_GET['StoppageTime'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = "MANAN_1718";
echo $id.$team.$kitnum.$type.$time.$stptime;
$conn = new mysqli($servername, $username, $password,$dbname);
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
//if ($type == 'FIRST') {
//    $sql = "DELETE FROM ".$id." WHERE EventType = 'FIRST' and Team = '".$team."'";
//    echo $sql;
//    $conn->query($sql);
//}
$sql = "SELECT Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Kitnumber = ".$kitnum;
$result = $conn->query($sql);
$extrainfo = null;
while ($row = $result->fetch_assoc()) {
    $name = $row['Name'];
    $extrainfo = $row['ExtraInfo'];
}
echo $name.$extrainfo;
$sql = "INSERT INTO ".$id." (Team, KitNumber, Name, ExtraInfo, EventType,EventTime, StoppageTime) VALUES ('".$team."','".$kitnum."','".$name."','".$extrainfo."','".$type."','".$time."','".$stptime."')";
$conn->query($sql);
$conn->close();
?>
