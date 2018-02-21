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
<?php
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['Match'];
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
if (preg_match('/^MANAN.+/', $dbname)) {
    $title = "马杯男足执场单";
}
if (preg_match('/^MANYU.+/', $dbname)) {
    $title = "马杯女足执场单";
}
echo "<h2>".$title."</h2>";
$sql = "SELECT TeamName FROM Teams";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $teams[] = $row['TeamName'];
}
?>
    主队: <select name="hometeam">
        <?php 
            for ($i = 0;$i < count($teams);$i++) {
                echo "<option>".$teams[$i];
            }
        ?>
         </select>
    <br>
    客队: <select name="awayteam">
        <?php 
            for ($i = 0;$i < count($teams);$i++) {
                echo "<option>".$teams[$i];
            }
        ?>
         </select>


   <input type="submit" name="submit" value="Submit" onclick="Submit()"> 
            <script>
            function Submit() {
                var ht = $("[name=hometeam]").val();
                var at = $("[name=awayteam]").val();
                console.log(ht,at);
                $.get("creatsheet.php", {
                    hometeam: ht,
                    awayteam: at,
                    dbname: '<?=$dbname ?>'
                }, function(data,state) {
                    console.log(data);
                    $("h3").after($("<p></p>").text(data));
                    url = "sheets/" + data;
                    $("#download").attr('href',url);
                    $("#download").attr('disabled',false);
                })
            }
            </script>
<?php
// 定义变量并默认设为空值
//$homename = $awayname = "";
//
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//      $homename = test_input($_POST["hometeam"]);
//      $awayname = test_input($_POST["awayteam"]);
//      $output = exec("python3 /var/www/TUFA/docreate.py $homename $awayname 2>&1",$arr,$ret);
//      exec("cp $arr[1] /var/www/html/sheets/");
//     // print_r($arr); 
//}
//
//function test_input($data) {
//   $data = trim($data);
//   $data = stripslashes($data);
//   $data = htmlspecialchars($data);
//   return $data;
//}
?>

<h3>下载:</h3>
<a class="btn btn-default" id="download" href="" disabled="true">下载</a>
  </body>
</html>



