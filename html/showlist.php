<?php
require "dbinfo.php";
$time = $_GET['time'];
$dbname = $_GET['dbname'];
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
//print_r($arr);
$elifile = fopen($dbname.'.json','r');
$eliinfo = json_decode(fgets($elifile));
$eliinfo = $eliinfo[1];
fclose($elifile);
$conn = dbconnect($dbname);
$sql = "SELECT * FROM Matches";
$result = $conn->query($sql);
$matches = array();
while ($row = $result->fetch_assoc()) {
    if ($row['Stage'] != 'Group') {
        foreach ($eliinfo as $key => $value) {
            if ($row['MatchID'] == $value->matchid) {
                $row['HomeTeam'] = $value->hometeam;
                $row['AwayTeam'] = $value->awayteam;
            }
        }
    }
    $matches[] = $row;
}
echo json_encode($matches);
$conn->close();
?>
