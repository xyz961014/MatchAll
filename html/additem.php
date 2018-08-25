<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require 'TeamDict.php';
    require 'dbinfo.php';
    $id = $_GET['MatchID'];
    $team = $_GET['Team'];
    $kitnum = $_GET['KitNumber'];
    $name = $_GET['Name'];
    $type = $_GET['Type'];
    $time = $_GET['Time'];
    $stptime = $_GET['StoppageTime'];
    $dbname = $_GET['dbname'];
    $conn = dbconnect($dbname);
    //echo $id.$team.$kitnum.$type.$time.$stptime;
    $sql = "SELECT Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Kitnumber = ".$kitnum." AND Name = '".$name."'";
    $result = $conn->query($sql);
    $extrainfo = null;
    if ($type != '弃赛'){
        while ($row = $result->fetch_assoc()) {
            $name = $row['Name'];
            $extrainfo = $row['ExtraInfo'];
        }
    }
    
    echo $name.$extrainfo;
    $sql = "INSERT INTO ".$id." (Team, KitNumber, Name, ExtraInfo, EventType,EventTime, StoppageTime) VALUES ('".$team."','".$kitnum."','".$name."','".$extrainfo."','".$type."','".$time."','".$stptime."')";
    echo $sql;
    $conn->query($sql);
    $conn->close();

}
?>
