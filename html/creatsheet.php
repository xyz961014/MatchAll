<?php
$homename = $_GET['hometeam'];
$awayname = $_GET['awayteam'];
$dbname = $_GET['dbname'];
$output = exec("python3 /var/www/TUFA/docreate.py $homename $awayname $dbname 2>&1",$arr,$ret);
exec("cp $arr[1] /var/www/html/sheets/");
print_r($arr);
echo $arr[2];
?>
