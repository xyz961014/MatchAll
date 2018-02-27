<?php
$table = $_GET['table'];
$sort = $_GET['sort'];
$asort = $_GET['asort'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['dbname'];
$time = $_GET['time'];
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
$sql = "SELECT * FROM ".$table." WHERE ".$sort." != 0 ORDER BY ".$sort." DESC";
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
