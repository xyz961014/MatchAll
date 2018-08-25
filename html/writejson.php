<?php
$name = $_POST['name'];
$str = $_POST['str'];
$file = fopen($name.'.json','w');
fwrite($file,$str);
fclose($file);
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$name." 2>&1",$arr,$ret);
print_r($arr);
?>
