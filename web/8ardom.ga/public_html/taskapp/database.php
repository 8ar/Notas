<?php
//Prueba sync
// par
//$conn = mysqli_connect("localhost:3306","admin_C_DRub ","Eljgv3wB4n","admin_C_DRub");
session_start();
$_SESSION['logged']=false;
$email ="";
$password="";
$password_r="";
$msg="";


define("DB_HOST", "localhost");
 define("DB_USER", "admin_C_DRub");
 define("DB_PASSWORD", "zqZLcM3LiZ");
 define("DB_DATABASE", "admin_C_DRub");


//$conn = new PDO('mysql:host=localhost;dbname=admin_C_DRub', DB_USER, DB_PASSWORD);
//declaracion de variables para poder conectarse



$email=strip_tags($_POST['email']);
$password=strip_tags($_POST['password']);

if($email==""){
$msg="Debe ingresar un mail <br>";
}elseif ($password=="") {
  // code...
  $msg="Debe ingresar una clave <br>";
} else {

if ($conn=false) {
  echo "Hubo un problema al conectarse a la Base de datos";
  die();
  // code...
}
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$password=sha1($password);
$query="SELECT * FROM  users WHERE users_email= '".$email."' AND users_password='".$password."';";

$result = $conn->query($query);

$users = $result->fetch_All(MYSQLI_ASSOC);

//cuento cuantos elementos tiene mysql_tablename MYSQLI_ASSOC
$count = count($users);



else{
  $msg ="Acceso denegado";
  $_SESSION['logged']=false;
}


}




 ?>
