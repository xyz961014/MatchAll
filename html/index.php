<!doctype html>
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
<ul id="navbar" class="nav nav-tabs">
    <li class='active'>
        <a href="#MANAN" data-toggle="tab"> 马杯男足 </a>
    </li>
    <li>
        <a href="#MANYU" data-toggle="tab"> 马杯女足 </a>
    </li>
    <li>
        <a href="#MAWU" data-toggle="tab"> 马杯五人制 </a>
    </li>
    <li>
        <a href="#NANQI" data-toggle="tab"> 小世界杯 </a>
    </li>
    <li>
        <a href="#FRESH" data-toggle="tab">新生杯</a>
    </li>
</ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="MANAN">
            <a href="sheet.php?Match=MANAN_1718">马杯男足执场单</a>
            <br>
            <a href="schedule.php?Match=MANAN_1718">马杯男足赛程</a>
            <br>
            <a href="ranktable.php?Match=MANAN_1718">马杯男足积分表</a>
        </div>
        <div class="tab-pane fade" id="MANYU">
            <a href="sheet.php?Match=MANYU_18">马杯女足执场单</a>
            <br>
            <a href="schedule.php?Match=MANYU_18">马杯女足赛程</a>
            <br>
            <a href="ranktable.php?Match=MANYU_18">马杯女足积分表</a>

        </div>
        <div class="tab-pane fade" id="MAWU">
            <a href="sheet.php?Match=MAWU_18">马杯五人制执场单</a>

        </div>
        <div class="tab-pane fade" id="NANQI">
            <a href="sheet.php?Match=NANQI_18">小世界杯执场单</a>
            <br>
            <a href="schedule.php?Match=NANQI_18">小世界杯赛程</a>
            <br>
            <a href="ranktable.php?Match=NANQI_18">小世界杯积分表</a>

        </div>
        <div class="tab-pane fade" id="FRESH">
            <a href="sheet.php?Match=FRESHMANCUP_18">新生杯执场单</a>
            <br>
            <a href="schedule.php?Match=FRESHMANCUP_18">新生杯赛程</a>
            <br>
            <a href="ranktable.php?Match=FRESHMANCUP_18">新生杯积分表</a>

        </div>


    <div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
$tabnum = $_GET['tab'];
?>
<script>
var tab = '<?=$tabnum ?>';
$('#navbar li:eq('+ tab.toString() +') a').tab('show');
</script>
  </body>
</html>
