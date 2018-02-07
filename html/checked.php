<?php
$id = $_GET['MatchID'];
$vbool = $_GET['Valid'];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['dbname'];
//print_r($arr);
if (!$vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -n ".$dbname." -d ".$id." 2>&1",$arr,$ret);
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
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
if ($vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -n ".$dbname." -a ".$id." 2>&1",$arr,$ret);
    print_r($arr);
}
?>
