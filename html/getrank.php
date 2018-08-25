<?php
require "dbinfo.php";
$table = $_GET['table'];
$sort = $_GET['sort'];
$asort = $_GET['asort'];
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
$order = $_GET['order'];
$time = $_GET['time'];
if ($table == "Players") {
    $fields = "Name, Team, Level, ".$sort;
    if ($asort) {
        $fields .= ", ".$asort;
    }
    $level = " INNER JOIN Teams ON Players.Team = Teams.TeamName ";
} else if ($table == "Teams") {
    $fields = "TeamName, Level, ".$sort;
    if ($asort) {
        $fields .= ", ".$asort;
    }
    $level = "";
}
$sql = "SELECT ".$fields." FROM ".$table.$level." WHERE ".$sort." != 0 ORDER BY ".$sort.$order;
if ($asort) {
    $sql = $sql.",".$asort." ASC";
}
$res = $conn->query($sql);
$info = array();
while ($row = $res->fetch_assoc()) {
    $info[] = $row;
}
echo json_encode($info);


$conn->close();
?>
