<!doctype html>
<?php
require "dbinfo.php";
$dbname = $_GET['Match'];
$conn = dbconnect($dbname);
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
    <?php echo "<a href='index.php?tab=".$dbname."'>返回</a>"; ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <div class="container">
    <p class='list'></p>
<script>
//console.log('load');
var d = new Date();
var dbname = '<?=$dbname?>';
$.getJSON(dbname + ".json",{
    time:d.getTime()
}, function(data,state) {
    var eliinfo = data[1];
})
$.get('showlist.php',{
    dbname: '<?=$dbname ?>',
    time: d.getTime()
}, function(data,state) {
    var list = $('.list');
    list.append(data);
    //console.log('load',data);
})
function onvalid(id) {
    var validcheck = document.getElementById(id);
    if (validcheck.checked) {
        console.log('true');
        var validbool = 1;
    } else {
        console.log('false');
        var validbool = 0;
    }
    $.get('checked.php',{
        dbname: '<?=$dbname ?>',
        MatchID: id,
        Valid: validbool
    },function(data,state) {
        console.log(data);
    })
}

</script>
<?php
$conn->close();
?>
</div>
  </body>
</html>

