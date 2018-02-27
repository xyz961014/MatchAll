<?php
require 'TeamDict.php';

$id = $_GET['MatchID'];
$team = $_GET['Team'];
$kitnum = $_GET['KitNumber'];
$name = $_GET['Name'];
$ktnf = $_GET['IsKit'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['dbname'];
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
if ($ktnf == "true") {
    $sql = "SELECT Kitnumber,Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Kitnumber = ".$kitnum;
} else {
    $sql = "SELECT Kitnumber,Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Name = '".$name."'";
}
$result = $conn->query($sql);
$extrainfo = null;
while ($row = $result->fetch_assoc()) {
    $kitnum = $row['Kitnumber'];
    $name = $row['Name'];
    $extrainfo = $row['ExtraInfo'];
}
$ans['kitnum'] = $kitnum;
$ans['name'] = $name;
$ans['extrainfo'] = $extrainfo;
echo json_encode($ans);
$conn->close();
?>
