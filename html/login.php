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
<?php //回到原先页面 
require "session.php";
if (@$_POST["username"])
    $username = $_POST["username"];
if (@$_POST["password"])
    $password = $_POST["password"];
?>
<?php echo "<a href='./teammanage.php?Match=".$dbname."'>返回</a>"; ?>
<h3>登录</h3>
<div class="container">
<form role="form" action="./login.php?>" method="post" id="teamform">
    <div class="form-group">
      <label for="username">用户名(Email)<span style="color:red">*</span></label>
      <input type="text" class="form-control" name="username" placeholder="请输入清华足联网站的用户名(Email)" required="">
    </div>
    <div class="form-group">
      <label for="password">密码<span style="color:red">*</span></label>
      <input type="password" class="form-control" name="password" placeholder="请输入密码" required="">
    </div>
  <button type="submit" class="btn btn-default">提交</button>
</form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
var username = "<?=$username ?>";
var password = "<?=$password ?>";
if (username != "") {
    $.post("https://www.tafa.org.cn/member/login_simple.php",
    {
        username: username,
        password: password
    }, function(state, data) {
        console.log(data)
    })

}
</script>
  </body>
</html>
