<?php
require "dbinfo.php";
$time = $_GET['time'];
$conn = dbconnect("MATCHES");
$sql = "SELECT * FROM matches";
$result = $conn->query($sql);
$matches = array();
while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
}
echo json_encode($matches);
$conn->close();
?>
