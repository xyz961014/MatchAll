<!doctype html>
<?php 
require "dbinfo.php";
$dbname = "MATCHES";
$conn = dbconnect($dbname);
$sql = "SELECT * FROM matches";
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
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">


    <title>TUFA</title>
  </head>
  <body>
<div class="container">
<a class="btn btn-default btn-sm pull-right" href="matchmanage.php">比赛管理</a>
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
        echo "<div class='tab-pane fade' id='".$t['dbname']."'> <h4>".$t['name']."</h4> <a href='teammanage.php?Match=".$t['dbname']."'>球队管理</a> <br><a href='sheet.php?Match=".$t['dbname']."'>执场单</a> <br> <a href='schedule.php?Match=".$t['dbname']."'>赛程</a> <br> <a href='ranktable.php?Match=".$t['dbname']."'>积分表</a> </div>";
    }
}
?>
    <div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
$('#navbar li:eq(0) a').tab('show');
var taba = '<?=$taba ?>';
var tabb = '<?=$tabb ?>';
console.log(taba, tabb);
if (tabb == -1)
    $('#navbar li:eq('+ taba.toString() +') a').tab('show');
else
    $('#navbar li:eq('+ taba.toString() +') li:eq('+ tabb.toString() +') a').tab('show');

</script>
  </body>
</html>
