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
    <link href="static/bootstrap.min.css" rel="stylesheet">
    <link href="static/bootstrap-theme.min.css" rel="stylesheet">

    <link href="static/ladda-themeless.min.css" rel="stylesheet">
    <script src="static/spin.min.js"></script>
    <script src="static/ladda.min.js"></script>
    
    <title>TUFA</title>
  </head>
  <body>
    <?php echo "<a href='index.php?tab=".$dbname."'>返回</a>"; ?>
    <?php require "session.php";?>

    <!-- Optional JavaScript -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="static/jquery.min.js"></script>
    <script src="static/bootstrap.min.js"></script>
<?php
if ($right > 1) {
$sql = "SELECT * FROM Info";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $title = $row['subname']."执场单";
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


    <button class="btn btn-primary ladda-button submit" data-style="expand-right" name="submit" onclick="Submit()">
        <span class="ladda-label">提交</span>
    </button>
            <script>
            Ladda.bind('.submit');
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
                    $(".filename").text(data);
                    url = "sheets/" + data;
                    $("#download").attr('href',url);
                    $("#download").attr('disabled',false);
                    var btn = Ladda.create(document.querySelector(".submit"));
                    btn.stop();
                })
            }
            </script>

<h3>下载:</h3>
<p class='filename'></p>
<a class="btn btn-default" id="download" href="" disabled="true">下载</a>
<?php
} else {
    echo "您没有权限查看这些内容，请登录后查看！"; 
}
$conn->close();
?>
  </body>
</html>



