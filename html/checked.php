<?php
require "dbinfo.php";
$id = $_GET['MatchID'];
$vbool = $_GET['Valid'];
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
//print_r($arr);
if (!$vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -n ".$dbname." -d ".$id." 2>&1",$arr,$ret);
    print_r($arr);
}
$sql = "UPDATE Matches SET Valid = ".$vbool." WHERE MatchID = ".$id;
echo $sql;
$conn->query($sql);
if ($vbool) {
    $output = exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Stat.py -n ".$dbname." -a ".$id." 2>&1",$arr,$ret);
    print_r($arr);
}
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Suspension.py ".$dbname." 2>&1",$arr,$ret);
$conn->close();
?>
