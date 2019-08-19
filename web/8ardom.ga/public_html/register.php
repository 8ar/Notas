

<?php
//$conn = mysqli_connect("localhost:3306","admin_C_DRub ","Eljgv3wB4n","admin_C_DRub");

//Declaracion de constantes para poder hacer la conexion completa a la base de datos

define("DB_HOST", "localhost");
 define("DB_USER", "admin_C_DRub");
 define("DB_PASSWORD", "zqZLcM3LiZ");
 define("DB_DATABASE", "admin_C_DRub");

 $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
//$conn = new PDO('mysql:host=localhost;dbname=admin_C_DRub', DB_USER, DB_PASSWORD);


$email ="";
$password="";
$password_r="";
$msg="";

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_r']) && isset($_POST['name'])){


  $email=strip_tags($_POST['email']);
  $password=strip_tags($_POST['password']);
  $password_r=strip_tags($_POST['password_r']);
  $username=strip_tags($_POST['name']);


  if($password==$password_r){



    //Buscar si ya hay un mail registrado en la base de datos
    $query="SELECT * FROM  users WHERE users_email= '".$email."';";
    $result = $conn->query($query);
    $users = $result->fetch_All(MYSQLI_ASSOC);
    // echo $users;
    //Contar cuantos elementos tiene mysql_tablename MYSQLI_ASSOC
    $count = count($users);

    //Si la no hay ningun email registrado este lo registrara y hara una rutina para agregar los datos en la base de datos
    if ($count==0) {
      //Encriptar la contraseña para que se guarde en la base de datos
      $password=sha1($password);
      //Agregar los datos del usuario en la tabla users para poder tomar los datos de ahi
      $query="INSERT INTO users (users_email,users_password,users_name) VALUES ('".$email."','".$password."','".$username."');";
      $sth = $conn->prepare($query);
      $sth->execute();
      //##Agregar una rutina de verificacion que si agregan los datos conforme se estan ejecutando los querys
      //Aprender a hacer rutinas
      //Extraer el id que se le asigno para poder asignarlo en la tabla de los datos
      //Falta hacer una rutina general para crear el id en la base de datos

      $query="SELECT * FROM  users WHERE users_email= '".$email."' LIMIT 1;";
      $result = $conn->query($query);
      $users = $result->fetch_All(MYSQLI_ASSOC);

      foreach ($users as $usersid ) {
        // code...
        $userid=$usersid['user_id'];
      }



      //Agregar los datos del usuario en las tablas mqtt user y mqtt acl para que tenga acceso a los datos que se va a publicar o se va a subscribir
      //mqtt user
      //Agregar una verificacion que el usuario sea

      $query="INSERT INTO mqtt_user (userid,username,password,salt,is_superuser) VALUES ('".$userid."','".$username."','".$password."','sha1',0);";
      $sth = $conn->prepare($query);
      $sth->execute();
      //mqtt acl
      // Creacion de topico por el usuario

      $usertopic=$userid."/#";
      $query="INSERT INTO mqtt_acl (allow,username,clientid,access,topic) VALUES (1,'".$username."','".$userid."',3,'".$usertopic."');";
      echo $query;
      $sth = $conn->prepare($query);
      $sth->execute();



      $msg="Usuario creado correctamente ingrese haciendo <a href = login.php>clic aqui</a><br>";

    }elseif ($count==1) {
      $msg ="Ya existe el usuario";
    }
    else{
      $msg ="Las claves no coinciden";
    }

  }

}else {
  $msg="Complete el formulario";
}

 ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Flatkit - HTML Version | Bootstrap 4 Web App Kit with AngularJS</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="../assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="../assets/images/logo.png">

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
        Sign up to your IOT House
      </div>
      <form method="post" target="register.php" name="form">
        <div class="md-form-group">
          <input name ="name" type="text" class="md-input" required>
          <label>Name</label>
        </div>
        <div class="md-form-group">
          <input name = "email" type="email" class="md-input" value=<?php echo $email ?> required>
          <label>Email</label>
        </div>
        <div class="md-form-group">
          <input name="password" type="password" class="md-input" required>
          <label>Password</label>
        </div>
        <div class="md-form-group">
          <input name="password_r" type="password" class="md-input" required>
          <label>Password</label>
        </div>
        <div class="m-b-md">
          <label class="md-check">
            <input type="checkbox" required><i class="primary"></i> Agree the <a href>terms and policy</a>
          </label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Sign up</button>
      </form>
    </div>
    <br>
<div style = "color:red" class="">
  <?php echo $msg; ?>
</div>

    <br>

    <div class="p-v-lg text-center">
      <div>Already have an account? <a ui-sref="access.signin" href="signin.html" class="text-primary _600">Sign in</a></div>
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
<!-- endbuild -->
</body>
</html>
