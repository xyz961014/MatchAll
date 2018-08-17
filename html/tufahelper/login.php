<?php

if (@$_POST["username"])
    $username = $_POST["username"];
if (@$_POST["password"])
    $password = $_POST["password"];
echo json_encode(Array($username, $password));

?>
