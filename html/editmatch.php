<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
    $dbname = $_POST['dbname'];
    $name = $_POST['name'];
    $subname = $_POST['subname'];
    $maxonfield = $_POST['maxonfield'];
    $minonfield = $_POST['minonfield'];
    $enablekitnum = $_POST['enablekitnum'];
    $class = $_POST['class'];
    $penalty = $_POST['penalty'];
    $ordinarytime = $_POST['ordinarytime'];
    $extratime = $_POST['extratime'];
    $penaltyround = $_POST['penaltyround'];
    $year = $_POST['year'];
    $conn = dbconnect("MATCHES");
    $sql = "UPDATE matches SET name = '".$name."' , subname = '".$subname."' , maxonfield = ".$maxonfield." , minonfield = ".$minonfield." , enablekitnum = ".$enablekitnum." , class = '".$class."' , penalty = '".$penalty."' , ordinarytime = ".$ordinarytime." , extratime = ".$extratime." , penaltyround = ".$penaltyround." , year = '".$year."' WHERE dbname = '".$dbname."'";
    echo $sql;
    $res = $conn->query($sql);
    $conn->close();
    $conn = dbconnect($dbname);
    $sql = "UPDATE Info SET name = '".$name."' , subname = '".$subname."' , maxonfield = ".$maxonfield." , minonfield = ".$minonfield." , enablekitnum = ".$enablekitnum." , class = '".$class."' , penalty = '".$penalty."' , ordinarytime = ".$ordinarytime." , extratime = ".$extratime." , penaltyround = ".$penaltyround." , year = '".$year."'";
    $res = $conn->query($sql);
    $conn->close();

}
?>
