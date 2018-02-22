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
    <a href="index.php">返回</a>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <div class="container">
    <p class='list'></p>
<?php
$dbname = $_GET['Match'];
//$servername = "localhost";
//$username = "root";
//$password = "961014";
//$dbname = "MANAN_1718";
//
//$conn = new mysqli($servername, $username, $password,$dbname);
//if ($conn->connect_error) {
//    die("Connection failed:".$conn->connect_error);
//}
//$sql = "SELECT * FROM Matches";
//$result = $conn->query($sql);
//if($result->num_rows > 0) {
//    while($row = $result->fetch_assoc()) {
//        echo $row['MatchID'].$row["HomeTeam"].$row["AwayTeam"].$row["MatchTime"].$row["MatchField"];
//        $id = $row['MatchID'];
//        $valid = $row['Valid'];
//        if ($valid == '1')
//            $valid = "checked='checked'";
//        else
//            $valid = '';
//        echo "<input name='validcheck' type='checkbox' ".$valid." id='$id' onclick='onvalid($id)' />VALID";
//        echo "<a href='match.php?id=$id'>Add</a>";
//        echo "<br>";
//    }
//} else {
//    echo "No record.";
//}
//$conn->close();
//
?>
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
</div>
  </body>
</html>

