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

    <link href="https://cdn.bootcss.com/Ladda/1.0.6/ladda-themeless.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/Ladda/1.0.6/spin.min.js"></script>
    <script src="https://cdn.bootcss.com/Ladda/1.0.6/ladda.min.js"></script>
    
    <title>TUFA</title>
  </head>
  <body>
    <?php echo "<a href='index.php?tab=".$dbname."'>返回</a>"; ?>
    <?php require "session.php";?>

    <!-- Optional JavaScript -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
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
<?php
$conn->close();
?>

<h3>下载:</h3>
<p class='filename'></p>
<a class="btn btn-default" id="download" href="" disabled="true">下载</a>
  </body>
</html>



