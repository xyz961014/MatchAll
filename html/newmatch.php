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
      <a href="./matchmanage.php">返回</a>
      <?php require "session.php"; ?>
<h3>增加新比赛</h3>
<div class="container">
<form role="form" action="./addmatch.php" method="post" id="matchform" onsubmit="return false">
    <div class="form-group">
      <label for="name">比赛名称<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="name" placeholder="请输入比赛名称" required="">
    </div>
    <div class="form-group">
      <label for="subname">比赛简称<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="subname" placeholder="请输入比赛简称" required="">
    </div>
    <div class="form-group">
      <label for="maxonfield">最多上场人数<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="maxonfield" placeholder="请输入一个数字" required="">
    </div>
    <div class="form-group">
      <label for="minonfield">最少上场人数<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="minonfield" placeholder="请输入一个数字" required="">
    </div>
    <label for="enablekitnum">是否有球衣号码<span style="color:red">*</span></label>
    <div class="radio">
        <label>
            <input type="radio" name="enablekitnum" value="1" checked> 是
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="enablekitnum" value="0"> 否
        </label>
    </div>
    <div class="form-group">
      <label for="class">比赛系列代码<span style="color:red">*</span>(一旦填写无法更改)</label>
      <input type="text" class="form-control" pattern="[A-Z]+" name="class" placeholder="请输入大写英文字符串" required="">
    </div>
    <label for="penalty">点球大战选项<span style="color:red">*</span></label>
    <div class="radio">
        <label>
            <input type="radio" name="penalty" id="elimination" value="淘汰赛" checked> 淘汰赛
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="penalty" id="always" value="总是"> 总是
        </label>
    </div>
    <div class="form-group">
      <label for="ordinarytime">常规比赛时间<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="ordinarytime" placeholder="请输入分钟数" required="">
    </div>
    <div class="form-group">
      <label for="extratime">加时赛时间<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="extratime" placeholder="请输入分钟数" required="">
    </div>
    <div class="form-group">
      <label for="penaltyround">点球轮数<span style="color:red">*</span></label>
      <input type="number" class="form-control" name="penaltyround" placeholder="请输入一个数字" required="">
    </div>
    <div class="form-group">
      <label for="year">年份<span style="color:red">*</span>(一旦填写无法更改)</label>
      <input type="text" class="form-control" pattern="[0-9-]+" name="year" placeholder='请输入年份，跨年比赛年份之间用"-"连接，年份范围为1970-2069。例：2017-2018' required="">
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
        var check = true;
        var maxonfield = $("[name='maxonfield']").val();
        var minonfield = $("[name='minonfield']").val();
        var classname = $("[name='class']").val();
        var year = $("[name='year']").val();
        console.log(maxonfield, minonfield, classname, year);
        
        var parseyear = year.split("-");
        console.log(parseyear);
        if (parseyear.length > 2) {
            check = false;
            alert("年份格式错误！");
            return;
        }
        if (parseyear.length == 2) {
            for (var i = 0;i < 2;i++) {
                if (parseInt(parseyear[i]) < 1970 || parseInt(parseyear[i]) > 2069) {
                    check = false;
                    alert("年份超出范围！");
                    return;
                }
            }
        }
        if (check) {
            if (parseyear.length == 1)
                var dbname = classname + "_" + parseyear[0].substr(2);
            if (parseyear.length == 2)
                var dbname = classname + "_" + parseyear[0].substr(2) + parseyear[1].substr(2);
        }
        if (maxonfield < minonfield) {
            check = false;
            alert("最多上场人数小于最少上场人数！");
            return;
        }
        console.log(dbname);
        if (check) {
            $.get("checkduplicate.php", {
                dbname: "MATCHES",
                table: "matches",
                field: "dbname",
                value: dbname
            }, function(data, state) {
                console.log(data);
                if (data == 1){
                    check = false;
                    alert("重复的比赛代码+年份！");
                    return;
                } else {
                    $("#matchform").attr("onsubmit", "return true");
                    $("#matchform").submit();
                }
            })
        }
    }

</script>
  </body>
</html>
