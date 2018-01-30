<?php
$id = $_GET['MatchID'];
$vbool = $_GET['Valid'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = "MANAN_1718";
if (!$vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -d ".$id." 2>&1",$arr,$ret);
    print_r($arr);
}
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
$sql = "UPDATE Matches SET Valid = ".$vbool." WHERE MatchID = ".$id;
echo $sql;
$conn->query($sql);
$conn->close();
if ($vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -a ".$id." 2>&1",$arr,$ret);
    print_r($arr);
}
?>
