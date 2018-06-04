<?php
require "dbinfo.php"
$table = $_GET['table'];
$sort = $_GET['sort'];
$asort = $_GET['asort'];
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
$order = $_GET['order'];
$time = $_GET['time'];
$sql = "SELECT *,".$sort." FROM ".$table." WHERE ".$sort." != 0 ORDER BY ".$sort.$order;
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
