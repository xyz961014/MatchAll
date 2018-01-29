<html>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<body>
<p class='list'></p>
<?php
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
$.get('showlist.php',{
    dbname: 'MANAN_1718',
    time: d.getTime()
}, function(data,state) {
    var list = $('.list');
    list.append(data);
    console.log('load',data);
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
        MatchID: id,
        Valid: validbool
    },function(data,state) {
        console.log(data);
    })
}

</script>
</body>
</html>
