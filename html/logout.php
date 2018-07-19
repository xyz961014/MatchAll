<?php
session_start();
$lasturl = $_SESSION["lasturl"];
$_SESSION = array();
header("location:".$lasturl);
?>
