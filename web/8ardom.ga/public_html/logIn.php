

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



if(isset($_POST['email']) && isset($_POST['password']) ){

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


if ($count==1) {

  $cookie_name = "user_id";
  $cookie_value = $users[0]['user_id'];
  setcookie($cookie_name, $cookie_value, time() + (86400 * 0.5), "/"); // 86400 = 1 day
  $cookie_name = "users_email";
  $cookie_value = $users[0]['users_email'];
  setcookie($cookie_name, $cookie_value, time() + (86400 * 0.5), "/"); // 86400 = 1 day
  $msg ="Exito!!";
  $_SESSION['logged']=true;
  echo '<meta http-equiv="refresh" content="2; url=dashboard.php">';
}
else{
  $msg ="Acceso denegado";
  $_SESSION['logged']=false;
}




  // code...
}


}

 ?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Domotica</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css ../assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->
  <div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar">
      <div class="pull-center">
        <div ui-include="'views/blocks/navbar.brand.html'"></div>
      </div>
    </div>
    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
      <div class="m-b text-sm">
        Log in with your Flatkit Account
      </div>
      <form method="post" target="" name="form" >
        <div class="md-form-group float-label">
          <input name = "email" type="email"  class="md-input" ng-model="user.email" required>
          <label>Email</label>
        </div>
        <div class="md-form-group float-label">
          <input name = "password" type="password"  class="md-input" ng-model="user.password" required>
          <label>Password</label>
        </div>
        <div class="m-b-md">
          <label class="md-check">
            <input type="checkbox"><i class="primary"></i> Keep me signed in
          </label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Sign in</button>
      </form>
    </div>
    <br>
  <div style = "color:red" class="">
  <?php echo $msg; ?>
  </div>
    <br>
    <div class="p-v-lg text-center">
      <div class="m-b"><a ui-sref="access.forgot-password" href="forgot-password.html" class="text-primary _600">Forgot password?</a></div>
      <div>Do not have an account? <a ui-sref="access.signup" href="signup.html" class="text-primary _600">Sign up</a></div>
    </div>
  </div>




<!-- ############ LAYOUT END-->

  </div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
  <script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
  <script src="libs/jquery/tether/dist/js/tether.min.js"></script>
  <script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
  <script src="libs/jquery/underscore/underscore-min.js"></script>
  <script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
  <script src="libs/jquery/PACE/pace.min.js"></script>

  <script src="assets/scripts/config.lazyload.js"></script>

  <script src="assets/scripts/palette.js"></script>
  <script src="assets/scripts/ui-load.js"></script>
  <script src="assets/scripts/ui-jp.js"></script>
  <script src="assets/scripts/ui-include.js"></script>
  <script src="assets/scripts/ui-device.js"></script>
  <script src="assets/scripts/ui-form.js"></script>
  <script src="assets/scripts/ui-nav.js"></script>
  <script src="assets/scripts/ui-screenfull.js"></script>
  <script src="assets/scripts/ui-scroll-to.js"></script>
  <script src="assets/scripts/ui-toggle-class.js"></script>

  <script src="assets/scripts/app.js"></script>

  <!-- ajax -->
  <script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
  <script src="assets/scripts/ajax.js"></script>

<!-- -->





<!-- -->






<!-- endbuild -->
</body>
</html>
