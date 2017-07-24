<?php
$connect = mysqli_connect("localhost", "root", "", "shopcart")or die(mysql_error());
mysqli_select_db($connect,"shopcart")or die(mysql_error());
?>