<?php
session_start();
$right = $_SESSION["right"];
if ($right > 1) {

    require "dbinfo.php";
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
    $parseyear = explode("-", $year);
    if (count($parseyear) == 1)
        $dbname = $class."_".substr($year, 2);
    if (count($parseyear) == 2)
        $dbname = $class."_".substr($parseyear[0], 2).substr($parseyear[1], 2);
    $conn = dbconnect("MATCHES");
    $sql = "INSERT INTO matches (dbname, name, subname, maxonfield, minonfield, enablekitnum, class, penalty, ordinarytime, extratime, penaltyround, year) VALUES ('".$dbname."','".$name."','".$subname."',".$maxonfield.",".$minonfield.",".$enablekitnum.",'".$class."','".$penalty."',".$ordinarytime.",".$extratime.",".$penaltyround.",'".$year."')";
    echo $sql;
    $conn->query($sql);
    $conn->close();
    $conn = dbconnect(false);
    $sql = "CREATE DATABASE ".$dbname;
    $conn->query($sql);
    $conn->close();
    $conn = dbconnect($dbname);
    $sql = "CREATE TABLE `Info` (
        `name` varchar(255) DEFAULT NULL,
        `subname` varchar(255) DEFAULT NULL,
        `maxonfield` int(11) DEFAULT NULL,
        `minonfield` int(11) DEFAULT NULL,
        `enablekitnum` tinyint(1) DEFAULT NULL,
        `class` varchar(255) DEFAULT NULL,
        `penalty` varchar(255) DEFAULT NULL,
        `ordinarytime` int(11) DEFAULT NULL,
        `extratime` int(11) DEFAULT NULL,
        `penaltyround` int(11) DEFAULT NULL,
        `year` varchar(255) DEFAULT NULL
    )";
    $conn->query($sql);
    $sql = "INSERT INTO Info (name, subname, maxonfield, minonfield, enablekitnum, class, penalty, ordinarytime, extratime, penaltyround, year) VALUES ('".$name."','".$subname."',".$maxonfield.",".$minonfield.",".$enablekitnum.",'".$class."','".$penalty."',".$ordinarytime.",".$extratime.",".$penaltyround.",'".$year."')";
    $conn->query($sql);
    $sql = "CREATE TABLE `Players` (
        `Team` varchar(255) NOT NULL,
        `Name` varchar(255) NOT NULL,
        `Class` varchar(255) DEFAULT NULL,
        `IDNumber` varchar(127) DEFAULT NULL,
        `PhoneNumber` varchar(255) DEFAULT NULL,
        `KitNumber` int(11) DEFAULT NULL,
        `ExtraInfo` varchar(255) DEFAULT NULL,
        `Appearances` int(11) DEFAULT '0',
        `Minutes` int(11) DEFAULT '0',
        `Goals` int(11) DEFAULT '0',
        `YellowCards` int(11) DEFAULT '0',
        `RedCards` int(11) DEFAULT '0',
        `Valid` tinyint(1) DEFAULT '1',
        `Suspension` tinyint(1) DEFAULT '0',
        `Penalties` int(11) DEFAULT '0',
        `Penaltymiss` int(11) DEFAULT '0',
        `OwnGoals` int(11) DEFAULT '0',
        `PlayerID` int(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`PlayerID`),
        UNIQUE KEY `IDNumber` (`IDNumber`)
    ) ";
    $conn->query($sql);
    $sql = "CREATE TABLE `Teams` (
        `TeamName` varchar(255) NOT NULL,
        `Level` varchar(255) DEFAULT NULL,
        `GroupName` varchar(255) DEFAULT NULL,
        `Win` int(11) DEFAULT '0',
        `Draw` int(11) DEFAULT '0',
        `Lose` int(11) DEFAULT '0',
        `Goal` int(11) DEFAULT '0',
        `Concede` int(11) DEFAULT '0',
        `Point` int(11) DEFAULT '0',
        `Penalty` int(11) DEFAULT '0',
        `YellowCard` int(11) DEFAULT '0',
        `RedCard` int(11) DEFAULT '0',
        `KitColor` varchar(255) DEFAULT NULL,
        `Penaltymiss` int(11) DEFAULT '0',
        `OwnGoal` int(11) DEFAULT '0',
        `TeamID` int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`TeamID`)
    ) ";
    $conn->query($sql);
    $sql = "CREATE TABLE `Matches` (
        `MatchID` int(11) NOT NULL AUTO_INCREMENT,
        `Level` varchar(255) DEFAULT NULL,
        `Stage` varchar(255) DEFAULT NULL,
        `GroupName` varchar(255) DEFAULT NULL,
        `Round` int(11) DEFAULT NULL,
      `MatchTime` datetime DEFAULT NULL,
      `MatchField` varchar(255) DEFAULT NULL,
      `HomeTeam` varchar(255) DEFAULT NULL,
      `AwayTeam` varchar(255) DEFAULT NULL,
      `HomeGoal` int(11) DEFAULT NULL,
      `AwayGoal` int(11) DEFAULT NULL,
      `PenaltyShootOut` tinyint(1) DEFAULT NULL,
      `HomePenalty` int(11) DEFAULT NULL,
      `AwayPenalty` int(11) DEFAULT NULL,
      `Result` varchar(255) DEFAULT NULL,
      `Valid` tinyint(1) DEFAULT NULL,
      PRIMARY KEY (`MatchID`)
    ) ";
    $conn->query($sql);
    $sql = "CREATE TABLE `Leaders` (
        `Team` varchar(255) NOT NULL,
        `Name` varchar(255) NOT NULL,
        `Job` varchar(255) NOT NULL,
        `PhoneNumber` varchar(255) DEFAULT NULL,
        `Email` varchar(255) DEFAULT NULL,
        `LeaderID` int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`LeaderID`)
    ) ";
    $conn->query($sql);
    $conn->close();
    header("location:./matchmanage.php");

}
?>
