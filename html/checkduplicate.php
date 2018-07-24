<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
    $dbname = $_GET['dbname'];
    $table = $_GET['table'];
    $field = $_GET['field'];
    $field2 = $_GET['field2'];
    $value = $_GET['value'];
    $value2 = $_GET['value2'];
    $extra = $_GET['extra'];
    $conn = dbconnect($dbname);
    $sql = "SELECT ".$field." FROM ".$table." WHERE ".$field." IS NOT NULL".$extra;
    $res = $conn->query($sql);
    $dup = 0;
    while($row = $res->fetch_assoc()) {
        if ($value == $row[$field]) {
            $dup = 1;
            break;
        }
    }
    if ($field2) {
    $sql = "SELECT ".$field2." FROM ".$table." WHERE ".$field2." IS NOT NULL".$extra;
    $res = $conn->query($sql);
    while($row = $res->fetch_assoc()) {
        if ($value2 == $row[$field2]) {
            $dup = 2;
            break;
        }
    }
    }
    $conn->close();
    echo $dup;

}
?>
