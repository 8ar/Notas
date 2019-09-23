<?php
$user_id=$_COOKIE['user_id'];

// session_start();
 // $user_id=$_SESSION['user_id'];
define("DB_HOST", "localhost");
 define("DB_USER", "admin_C_DRub");
 define("DB_PASSWORD", "zqZLcM3LiZ");
 define("DB_DATABASE", "admin_C_DRub");
//$conn = new PDO('mysql:host=localhost;dbname=admin_C_DRub', DB_USER, DB_PASSWORD);
//declaracion de variables para poder conectarse
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

 ?>
