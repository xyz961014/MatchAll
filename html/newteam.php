<!doctype html>
<?php
$dbname = $_GET['dbname'];
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
<?php echo "<a href='./teammanage.php?Match=".$dbname."'>返回</a>"; ?>
<?php require "session.php";?>
<?php
if ($right > 1) {
?>
<h3>增加新球队</h3>
<div class="container">
<form role="form" action="./addteam.php?dbname=<?php echo $dbname;?>" method="post" id="teamform" onsubmit="return false">
    <div class="form-group">
      <label for="teamname">球队名称<span style="color:red">*</span>(一旦填写无法更改)</label>
      <input type="text" class="form-control" name="teamname" placeholder="请输入球队名称" required="">
    </div>
    <div class="form-group">
      <label for="kitcolor">球衣颜色</label>
      <input type="text" class="form-control" name="kitcolor" placeholder="请输入球衣颜色">
    </div>
    <div class="form-group">
      <label for="level">球队级别</label>
      <input type="text" class="form-control" name="level" placeholder="请输入球队级别">
    </div>
    <div class="form-group">
      <label for="groupname">所属小组</label>
      <input type="text" class="form-control" pattern="[A-Z]+" name="groupname" placeholder='请输入大写英文字母'>
    </div>
  <button type="submit" class="btn btn-default" onclick="checkform()">提交</button>
</form>
</div>
<?php
} else {
    echo "您没有权限查看这些内容！";
}
?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
if ($right > 1) {
?>
<script>
function checkform() {
    var teamname = $("[name='teamname']").val();
    if (teamname) {
        $.get("checkduplicate.php", {
                dbname: "<?=$dbname ?>",
                table: "Teams",
                field: "TeamName",
                value: teamname
            }, function(data, state) {
                console.log(data);
                if (data == 1){
                    alert("重复的球队名称！");
                    return;
                } else {
                    $("#teamform").attr("onsubmit", "return true");
                    $("#teamform").submit();
                }
            })
    }
    
}
</script>
<?php
}
?>
  </body>
</html>
