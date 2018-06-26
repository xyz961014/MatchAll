<?php
require "dbinfo.php";
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
$time = $_GET['time'];

$elifile = fopen($dbname.'.json','r');
$eliinfo = json_decode(fgets($elifile));
$eliinfo = $eliinfo[1];
fclose($elifile);
$sql = "SELECT * FROM Matches";
$result = $conn->query($sql);
if($result->num_rows > 0) {
    echo "<div class = 'col-lg-12'><table class='table table-bordered table-hover table-condensed'><caption>赛程</caption><thead><tr><th>场序</th><th>主队</th><th>比分</th><th>客队</th><th>时间</th><th>场地</th><th>编辑</th></tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
        if ($row['Stage'] != 'Group') {
            foreach ($eliinfo as $key => $value) {
                if ($row['MatchID'] == $value->matchid) {
                    $row['HomeTeam'] = $value->hometeam;
                    $row['AwayTeam'] = $value->awayteam;
                }
            }
        }
        $id = $row['MatchID'];
        $valid = $row['Valid'];
        if ($row['Valid'])
            echo "<tr><td>".$row['MatchID']."</td><td>".$row['HomeTeam']."</td><td>".$row['HomeGoal'].":".$row['AwayGoal']."</td><td>".$row['AwayTeam']."</td><td>".$row['MatchTime']."</td><td>".$row['MatchField']."</td><td><a href='match.php?Match=".$dbname."&id=".$id."'><span class='glyphicon glyphicon-edit'></span></a></td></tr>";
        else
            echo "<tr><td>".$row['MatchID']."</td><td>".$row['HomeTeam']."</td><td>VS</td><td>".$row['AwayTeam']."</td><td>".$row['MatchTime']."</td><td>".$row['MatchField']."</td><td><a href='match.php?Match=".$dbname."&id=".$id."'><span class='glyphicon glyphicon-edit'></span></a></td></tr>";
    }
    echo "</tbody></table></div>";
} else {
    echo "No record.";
}
$conn->close();

?>
