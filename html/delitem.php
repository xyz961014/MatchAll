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
$dbname = $_GET['dbname'];
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
//$sql = "SELECT Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Kitnumber = ".$kitnum;
//$result = $conn->query($sql);
//$extrainfo = null;
//while ($row = $result->fetch_assoc()) {
//    $name = $row['Name'];
//    $extrainfo = $row['ExtraInfo'];
//}
//echo $name.$extrainfo;
$sqlkit = "AND KitNumber = '".$kitnum."'";
$sqltime = "AND EventTime = '".$time."'";
$sqlstptime = "AND StoppageTime = '".$stptime."'";
$sql = "DELETE FROM ".$id." WHERE EventType = '".$type."' AND Team = '".$team."'";
$sqls = "SELECT * FROM ".$id." WHERE EventType = '".$type."' AND Team = '".$team."'";
if ($kitnum != null){
    $sql = $sql.$sqlkit;
    $sqls = $sqls.$sqlkit;
} else if ($name != null) {
    $sqlname = "AND Name = '".$name."'";
    $sql = $sql.$sqlname;
    $sqls = $sqls.$sqlname;
} 
if ($time != null) {
    $sql = $sql.$sqltime;
    $sqls = $sqls.$sqltime;
}
if ($stptime != null) {
    $sql = $sql.$sqlstptime;
    $sqls = $sqls.$sqlstptime;
}
echo $sql;
$res = $conn->query($sqls);
$num = 0;
while($row = $res->fetch_assoc()) {
    $num = $num + 1;
}
echo $num;
$conn->query($sql);
for (;$num > 1;$num--) {
    $sql = "SELECT Name,ExtraInfo FROM Players WHERE Team = '".$team."' and Kitnumber = ".$kitnum;
    $result = $conn->query($sql);
    $extrainfo = null;
    while ($row = $result->fetch_assoc()) {
        $name = $row['Name'];
        $extrainfo = $row['ExtraInfo'];
    }
    echo $name.$extrainfo;
    $sql = "INSERT INTO ".$id." (Team, KitNumber, Name, ExtraInfo, EventType,EventTime, StoppageTime) VALUES ('".$team."','".$kitnum."','".$name."','".$extrainfo."','".$type."','".$time."','".$stptime."')";
    echo $sql;
    $conn->query($sql);

}
$conn->close();
?>
