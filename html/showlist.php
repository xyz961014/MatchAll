<?php
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['dbname'];
$time = $_GET['time'];

$elifile = fopen($dbname.'.json','r');
$eliinfo = json_decode(fgets($elifile));
$eliinfo = $eliinfo[1];
fclose($elifile);
//print_r($eliinfo);
//echo $eliinfo->13->hometeam;
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
$sql = "SELECT * FROM Matches";
$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['Stage'] != 'Group') {
            foreach ($eliinfo as $key => $value) {
                if ($row['MatchID'] == $value->matchid) {
                    $row['HomeTeam'] = $value->hometeam;
                    $row['AwayTeam'] = $value->awayteam;
                }
            }
        }
        echo $row['MatchID'].$row["HomeTeam"].$row["AwayTeam"].$row["MatchTime"].$row["MatchField"];
        $id = $row['MatchID'];
        $valid = $row['Valid'];
        if ($valid == '1')
            $valid = "checked='checked'";
        else
            $valid = '';
        echo "<input name='validcheck' type='checkbox' ".$valid." id='$id' onclick='onvalid($id)' />VALID";
        echo "<a href='match.php?Match=$dbname&id=$id'>Add</a>";
        echo "<br>";
    }
} else {
    echo "No record.";
}
$conn->close();

?>
