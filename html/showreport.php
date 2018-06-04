<?php
require 'TeamDict.php';
require "dbinfo.php";
$id = $_GET['MatchID'];
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
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
