<?php
session_start();
if (!empty($_SESSION["name"]))
    $name = $_SESSION["name"];
if (!empty($_SESSION["state"]))
    $state = $_SESSION["state"];
if (!empty($_SESSION["right"]))
    $right = $_SESSION["right"];
if (!empty($_SESSION["id"]))
    $id = $_SESSION["id"];
$lasturl = $_SERVER['PHP_SELF'];
if ($_SERVER["QUERY_STRING"]) {
    $lasturl .= "?".$_SERVER["QUERY_STRING"];
}
$_SESSION["lasturl"] = $lasturl;
if ($state){
    echo "<div class='pull-right'style='margin-right:20px;margin-left:20px;'><p>".$name."<a style='margin-left:20px' href='logout.php'>注销</a></p></div>";
} else {
    echo "<a class='pull-right' style='margin-left:20px;margin-right:20px;' href='login.php'>登录</a>";
}
?>
