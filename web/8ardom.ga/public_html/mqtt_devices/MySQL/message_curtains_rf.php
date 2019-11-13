
<?php

// Se recibe la informacion por el metodo post
$device=$_POST["device"];
$cmd1=$_POST["up"];
$cmd2=$_POST["down"];
$cmd3=$_POST["stop"];
$chnnl=$_POST["channel"];

// echo $cmd1;
// echo $cmd2;
// echo $cmd3;
// se conecta a la base de datos para poder sacar la informacion necesaria para mandar el mensaje de las cortinas_1
include('database.php');
$query = "SELECT * FROM curtains WHERE device_id = '$device';";
  //   WHERE devices_user_id = '$user_id' AND devices_id LIKE '00001%'";
  $result = mysqli_query($conn, $query);
  $info = $result->fetch_all(MYSQLI_ASSOC);
  // var_dump($info);
  $curtain=$info[0]["num_curtain"];
  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }
mysqli_close($conn);
// se cierra la conexion de la base de datos

//Se buscar el archivo donde vive el archivo JSON donde estan los comandos de la cortina porque su estructuracion no es
// es la mas correcta
$path="./json_commands/curtains_commands.json";
$strJsonFileContents = file_get_contents($path);
// Convert to array
$array = json_decode($strJsonFileContents, true);
$command="stop";
// echo $array["model"];
if ($cmd1=="true") {
  $command="up";
  // code...
}elseif ($cmd2=="true") {
  $command="down";
  // code...
}elseif ($cmd3=="true") {
  $command="stop";
  // code...
}

$channel=$chnnl;
// Se concatena los resultados para que sea solo la parte del comando de la cortina correspondiente
$message = $array["message"]["P1"].$array["message"]["P2"][$curtain].$array["message"]["P3"].$array["message"]["P4"][$channel].$array["message"]["P5"][$command];



$messagearray=array('N_M' =>0 ,'order' => array(),'values'=> array(),'bits' => array(),'type'=>3);
$bin=$message;
$length=strlen($bin);


$bytesmessage=round($length/32, 0);

$modBytemessage=$length-($bytesmessage*32);
//n es el numero de mensajes que se van a estar enviando
$n=0;

if ($bytesmessage>0) {
  for ($i=0; $i <$bytesmessage ; $i++) {
    $messagearray["values"][$i]=bindec(substr($bin, ($i)*32, 32));

    $messagearray["order"][$i]=$i+1;
    $messagearray["bits"][$i]=32;
    $n=$n+1;
    // echo dechex($messagearray["values"][$i]);
    // echo "___".$messagearray["N_M"][$i]."____";
    // echo "___".substr($bin, ($i)*32, 32)."____";
  }
}

if ($modBytemessage>0) {
  $n=$n+1;
  $messagearray["values"][$bytesmessage]=bindec(substr($bin,-$modBytemessage));
  $messagearray["order"][$bytesmessage]=$n;
  $messagearray["bits"][$bytesmessage]=$modBytemessage;
  // echo "___".substr($bin, -$modBytemessage)."____";
}
$messagearray["N_M"]=$n;

// $ind="sufix";
//
// if (array_key_exists($ind,$array))
// {
//   $n=$n+1;
//   $bits=strlen($array[$ind]);
//   $messagearray["values"][$n]=bindec($array[$ind]);
//   $messagearray["order"][$n]=$n;
//   $messagearray["bits"][$n]=$bits;
// }

$messagearray["N_M"]=$n;

echo json_encode($messagearray);




 ?>
