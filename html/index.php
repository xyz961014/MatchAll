<!doctype html>
<?php 
require "dbinfo.php";
$dbname = "MATCHES";
$conn = dbconnect($dbname);
$sql = "SELECT * FROM matches where owner = 0";
$result = $conn->query($sql);
$tabs = array();
while ($row = $result->fetch_assoc()) {
    if (!array_key_exists($row['class'], $tabs)) {
        $tabs[$row['class']] = array($row);
    }
    else {
        array_push($tabs[$row['class']], $row);
    }
}
asort($tabs);
?>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="static/bootstrap.min.css" rel="stylesheet">
    <link href="static/bootstrap-theme.min.css" rel="stylesheet">


    <title>TUFA</title>
  </head>
  <body>
<div class="container">
<?php require "session.php";?>
<?php
if ($right > 0) {
$sql = "SELECT * FROM matches WHERE owner = ".$id;
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    if (!array_key_exists($row['class'], $tabs)) {
        $tabs[$row['class']] = array($row);
    }
    else {
        array_push($tabs[$row['class']], $row);
    }
}
//asort($tabs);
}
if ($right > 1) {
?>
<a class="btn btn-default btn-sm pull-right" href="matchmanage.php">比赛管理</a>
<?php
}
?>
<ul id="navbar" class="nav nav-tabs">
<?php 
foreach ($tabs as $tab) {
    if (count($tab) == 1)
        echo "<li> <a href='#".$tab[0]['dbname']."' data-toggle='tab'>".$tab[0]['subname']."</a></li>";
    else {
        echo "<li class='dropdown'> <a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$tab[0]['subname']."<b class='caret'></b></a> <ul class='dropdown-menu'>";
        foreach($tab as $t) {
            echo "<li><a href='#".$t['dbname']."' data-toggle='tab'>".$t['year']."</a></li>";
        }
        echo "</ul></li>";
    }
}
?>
</ul>
    <div class="tab-content">
<?php
foreach ($tabs as $class => $tab) {
    foreach ($tab as $t) {
        echo "<div class='tab-pane fade' id='".$t['dbname']."'> <h4>".$t['name']."</h4> ";
        if ($right > 1) {
            echo "<a href='teammanage.php?Match=".$t['dbname']."'>球队管理</a> <br><a href='sheet.php?Match=".$t['dbname']."'>执场单</a> <br> ";
        }
        echo "<a href='schedule.php?Match=".$t['dbname']."'>赛程</a> <br> <a href='ranktable.php?Match=".$t['dbname']."'>积分表</a> </div>";
    }
}
?>
    <div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="static/jquery.min.js"></script>
    <script src="static/bootstrap.min.js"></script>
<?php
$tabname = $_GET['tab'];
$i = -1;
$j = -1;
foreach($tabs as $tab) {
    $i++;
    $j = -1;

    foreach($tab as $t) {
        if (count($tab) > 1)
            $j++;
        if ($t['dbname'] == $tabname) {
            $taba = $i;
            $tabb = $j;
            break;
        }
    }
}
$conn->close();
?>
<script>
$("[href='#MANAN_1819']").tab('show');
var taba = '<?=$taba ?>';
var tabb = '<?=$tabb ?>';
var tab = "";
<?php 
if ($tabname) {
?>
    var tab = '<?=$tabname ?>';
    $("[href='#" + tab + "']").tab('show');
<?php
}
?>

</script>
  </body>
</html>
