<!doctype html>
<?php
$dbname = $_GET['dbname'];
$team = $_GET['team'];
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
<?php echo "<a href='./playermanage.php?Match=".$dbname."&team=".$team."'>返回</a>";
echo "<h3>".$team." 增加新球员</h3>"?>
<div class="container">
<form role="form" action="./addplayer.php?dbname=<?php echo $dbname;?>&team=<?php echo $team;?>" method="post" id="playerform" onsubmit="return false">
    <div class="form-group">
      <label for="name">姓名<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="name" placeholder="请输入姓名" required="">
    </div>
    <div class="form-group">
      <label for="class">班级</label>
      <input type="text" class="form-control" name="class" placeholder="请输入班级">
    </div>
    <div class="form-group">
      <label for="idnumber">证件号码</label>
      <input type="text" class="form-control" name="idnumber" placeholder="请输入证件号码">
    </div>
    <div class="form-group">
      <label for="phonenumber">电话号码</label>
      <input type="number" class="form-control" name="phonenumber" placeholder='请输入电话号码' value="0">
    </div>
    <div class="form-group">
      <label for="kitnumber">球衣号码<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="kitnumber" max="99" min="0" placeholder="请输入球衣号码,范围为0-99" required="">
    </div>
    <div class="form-group">
      <label for="extrainfo">备注</label>
      <input type="text" class="form-control" name="extrainfo" placeholder="请输入备注，例如：队长、足特">
    </div>
    <label for="valid">是否报名<span style="color:red">*</span></label>
    <div class="radio">
        <label>
            <input type="radio" name="valid" value="1" checked> 是
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="valid" value="0"> 否
        </label>
    </div>

  <button type="submit" class="btn btn-default" onclick="checkform()">提交</button>
</form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
function checkform() {
    var kitnum = $("[name='kitnumber']").val();
    var idnum = $("[name='idnumber']").val();
    console.log(kitnum, idnum);
    if (kitnum) {
        $.get("checkduplicate.php", {
                dbname: "<?=$dbname ?>",
                table: "Players",
                field: "KitNumber",
                field2: "IDNumber",
                value: kitnum,
                value2: idnum,
                extra: " AND Team = '<?=$team ?>'"
            }, function(data, state) {
                console.log(data);
                if (data == 1){
                    alert("重复的球衣号码！");
                    return;
                } else if (data == 2) {
                    alert("重复的证件号码！");
                    return;
                } else {
                    $("#playerform").attr("onsubmit", "return true");
                    $("#playerform").submit();
                }
            })
    }
    
}
</script>
  </body>
</html>
