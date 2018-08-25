<?php
require 'TeamDict.php';
require 'dbinfo.php';

$id = $_GET['MatchID'];
$team = $_GET['Team'];
$kitnum = $_GET['KitNumber'];
$name = $_GET['Name'];
$ktnf = $_GET['IsKit'];
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
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
