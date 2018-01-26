<?php
require 'TeamDict.php';
$id = $_GET['MatchID'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = "MANAN_1718";
$conn = new mysqli($servername, $username, $password,$dbname);
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
$sql = "SELECT * FROM ".$id." ORDER BY EventTime,StoppageTime";
$result = $conn->query($sql);
$infos = array();
while ($row = $result->fetch_assoc()) {
    $info["team"] = $row['Team'];
    $info["kitnum"] = $row['KitNumber'];
    $info["name"] = $row['Name'];
    $info["extrainfo"] = $row['ExtraInfo'];
    $info["type"] = $row['EventType'];
    $info["time"] = $row['EventTime'];
    $info["stptime"] = $row['StoppageTime'];
    array_push($infos,json_encode($info));
}
//$info['Team'] = $team;
//$info['KitNumber'] = $kitnum;
//$info['Name'] = $name;
//$info['ExtraInfo'] = $extrainfo;
//$info['EventType'] = $type;
//$info['EventTime'] = $time;
//$info['StoppageTime'] = $stptime;
echo json_encode($infos);
$conn->close();
?>
