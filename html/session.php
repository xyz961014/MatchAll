<?php
session_start();
$username = "";
$state = false;
$right = 0;
if (!empty($_SESSION["username"]))
    $username = $_SESSION["username"];
if (!empty($_SESSION["state"]))
    $state = $_SESSION["state"];
if (!empty($_SESSION["right"]))
    $right = $_SESSION["right"];
if ($state){
    echo $username."<a class='pull-right'style='margin-right:20px' href='logout.php'>注销</a>";
} else {
    echo "<a class='pull-right' style='margin-right:20px' href='login.php'>登录</a>";
}
?>
