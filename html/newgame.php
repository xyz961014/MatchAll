<!doctype html>
<?php
require "dbinfo.php";
$dbname = $_GET['dbname'];
$conn = dbconnect($dbname);
$sql = "SELECT * FROM Info";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $subname = $row['subname'];
}
$sql = "SELECT * FROM Teams";
$res = $conn->query($sql);
$teams = array();
while ($row = $res->fetch_assoc()) {
    $teams[] = $row;
}
function printoption($teams) {
    for($i = 0;$i < count($teams);$i++) {
        echo "<option>".$teams[$i]['TeamName']."</option>";
    }
    echo "<option value='TBD'>待定</option>";
}
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
<?php echo "<a href='./schedule.php?Match=".$dbname."'>返回</a>";
require "session.php";
echo "<h3>".$subname." 增加新比赛</h3>" ?>
<div class="container">
<form role="form" action="./addgame.php?dbname=<?php echo $dbname;?>" method="post">
    <div class="form-group">
      <label for="hometeam">主队<span style="color:red">*</span></label>
      <select class="form-control" name="hometeam" placeholder='请选择主队' required="">
        <?php printoption($teams);?>
      </select>
    </div>
    <div class="form-group">
      <label for="awayteam">客队<span style="color:red">*</span></label>
      <select class="form-control" name="awayteam" placeholder='请选择客队' required="">
        <?php printoption($teams);?>
      </select>
    </div>
    <div class="form-group">
      <label for="level">比赛级别</label>
      <input type="text" class="form-control" name="level" placeholder="请输入比赛级别">
    </div>
    <div class="form-group">
      <label for="stage">比赛阶段<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="stage" placeholder='请输入比赛阶段，小组赛请输入"小组赛"' required="">
    </div>
    <div class="form-group">
      <label for="groupname">所属小组</label>
      <input type="text" class="form-control" pattern="[A-Z]+" name="groupname" placeholder='请输入大写英文字母'>
    </div>
    <div class="form-group">
      <label for="round">轮次</label>
      <input type="number" class="form-control" name="round" placeholder='请输入一个数字' value="null">
    </div>
    <div class="form-group">
      <label for="matchtime">比赛时间<span style="color:red">*</span></label>
      <input type="datetime-local" class="form-control" name="matchtime" placeholder="请选择比赛时间" required="">
    </div>
    <div class="form-group">
      <label for="matchfield">比赛场地<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="matchfield" placeholder='请输入比赛场地' required="">
    </div>
  <button type="submit" class="btn btn-default">提交</button>
</form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
$("select[name$='team']").click(function() {
    var val = $(this).val();
    var name = $(this).attr('name');
    console.log(val);
    if (val == "TBD") {
        var txtinput = "<input type='text' class='form-control " + name + "' name=" + name + " placeholder='请输入一个模式，以A1、A2表示A组第一和A组第二，以W17、L18表示场序17的比赛的胜者和场序18的比赛的负者' required=''>";
        $(this).attr('name', name + '*');
        $(this).after(txtinput);
    }
    else {
        $("." + name).remove();
    }
});
//$("select[name$='*']").click(function() {
//    var val = $(this).val();
//    var name = $(this).attr('name');
//    console.log(val, val,name);
//    if (val != "TBD") {
//        $("[name='']")
//    }
//})
</script>
<?php
$conn->close();
?>
  </body>
</html>
