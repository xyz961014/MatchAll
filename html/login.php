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
session_start();
$lasturl = $_SESSION["lasturl"];
echo "<a href='".$lasturl."'>返回</a>"; 
if (@$_POST["username"])
    $username = $_POST["username"];
if (@$_POST["password"])
    $password = $_POST["password"];

function post($url, $post_data = '', $timeout = 5){//curl
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        if($post_data != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
}
if ($username) {
$res = post("https://www.tafa.org.cn/member/login_simple.php", $post_data=["username"=>$username, "password"=>$password]);
if ($res) {
    $res = json_decode($res);
    $_SESSION["name"] = $res[5];
    $_SESSION["right"] = $res[2];
    $_SESSION["id"] = $res[6];
    $_SESSION["state"] = true;
    header("location:".$lasturl);
} else {
    echo "<script>alert('用户名或密码错误')</script>";
}
}


?>
<h3>登录</h3>
<div class="container">
<form role="form" action="./login.php" method="post">
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

</script>
  </body>
</html>
