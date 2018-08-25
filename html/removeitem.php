<?php
session_start();
$right = $_SESSION["right"];
if ($right > 2) {
    require "dbinfo.php";
    $key = $_GET["idkey"];
    $value = $_GET["idvalue"];
    $db = $_GET["db"];
    $table = $_GET["table"];
    $conn = dbconnect($db);
    $sql = "DELETE FROM ".$table." WHERE ".$key." = '".$value."'";
    $conn->query($sql);
    $conn->close();
    if ($db == "MATCHES") {
        $conn = dbconnect(false);
        $sql = "DROP DATABASE ".$value;
        $conn->query($sql);
        $conn->close();    
    }
    if ($table == "Matches") {
        $conn->dbconnect($db);
        $sql = "DROP TABLE Match".$value;
        $conn->query($sql);
        $conn->close();    
    }
}
?>
