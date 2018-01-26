<html>
<body>

<h2>马杯男足执场单</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   主队: <select name="hometeam">
           <option>生命科学学院
           <option>微纳电子系
           <option>数学科学系
           <option>软件学院
           <option>地球系统科学系
           <option>土木建管系
           <option>美术学院
           <option>法学院
           <option>新闻与传播学院
           <option>水利水电工程系
           <option>化学系
           <option>新雅书院
           <option>电机系
           <option>医学院
           <option>公共管理学院
           <option>社会科学学院
           <option>环境学院
           <option>核研院
           <option>五道口金融学院
           <option>热能工程系
           <option>交叉信息院
           <option>物理系
           <option>工程物理系
           <option>航天航空学院
           <option>电子工程系
           <option>机械工程系
           <option>经济管理学院
           <option>工业工程系
           <option>建筑学院
           <option>人文学院
           <option>苏世民书院
           <option>化学工程系
           <option>精密仪器系
           <option>计算机系
           <option>自动化系
           <option>材料学院
           <option>汽车工程系
         </select>
   <br>
   客队: <select name="awayteam">
           <option>生命科学学院
           <option>微纳电子系
           <option>数学科学系
           <option>软件学院
           <option>地球系统科学系
           <option>土木建管系
           <option>美术学院
           <option>法学院
           <option>新闻与传播学院
           <option>水利水电工程系
           <option>化学系
           <option>新雅书院
           <option>电机系
           <option>医学院
           <option>公共管理学院
           <option>社会科学学院
           <option>环境学院
           <option>核研院
           <option>五道口金融学院
           <option>热能工程系
           <option>交叉信息院
           <option>物理系
           <option>工程物理系
           <option>航天航空学院
           <option>电子工程系
           <option>机械工程系
           <option>经济管理学院
           <option>工业工程系
           <option>建筑学院
           <option>人文学院
           <option>苏世民书院
           <option>化学工程系
           <option>精密仪器系
           <option>计算机系
           <option>自动化系
           <option>材料学院
           <option>汽车工程系
         </select>


   <input type="submit" name="submit" value="Submit"> 
</form>
<?php
// 定义变量并默认设为空值
$homename = $awayname = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $homename = test_input($_POST["hometeam"]);
      $awayname = test_input($_POST["awayteam"]);
      $output = exec("python3 /var/www/TUFA/docreate.py $homename $awayname 2>&1",$arr,$ret);
      exec("cp $arr[1] /var/www/html/sheets/");
      //print_r($arr); 
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<?php
echo "<h2>下载:</h2>";
echo $arr[2];
?>
<?php $url = 'sheets/'.$arr[2]; ?>
<script type="text/javascript">
    function goto()
    {
        if ('<?=$arr[2]?>' != '') {
            window.location.href = '<?=$url ?>';
        }
    }
</script>
<button class="button" id="download" onclick="goto()">download</button>
</body>
</html>
