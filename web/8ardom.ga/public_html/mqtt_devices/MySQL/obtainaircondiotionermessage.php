<?php
$temp = "26";
$mode = "heat";
$sUp = false;
$sDown = false;
$spd = "AUTO";

if (isset($_POST["temperature"]) && isset($_POST["mode"]) && isset($_POST["sUp"]) && isset($_POST["sDown"]) && isset($_POST["spd"])) {
  $temp = $_POST["temperature"];
  $mode=$_POST["mode"];
  $sUp=$_POST["sUp"];
  $sDown=$_POST["sDown"];
  $spd=$_POST["spd"];
}

$b1 ="11000011";
$b2="11110010";
$b3="00000111";
$b4="00000000";
$b5="00000101";
$b6="00000000";
$b7="00000100";
$b8="00000000";
$b9="00000000";
$b10="00000100";
$b11="00000000";
$b12="00000000";
$b13="01001011";

////////////////////////////////////////////////////////////////////
function Temperatura($tempe, $sUp) {
  $binario = decbin($tempe - 8);
  $bit="";
  $init_by2="";
  $init_by2 = swingUp_by2($sUp);

	for ($i = 1; $i <= (strlen($binario)); $i++) {
	  $bit .= substr($binario, (-$i),1);
	}

	$bit2 = "$init_by2"."$bit";

  if ((strlen($bit2) == 7)){
	 $bit2 = "$init_by2"."$bit" ."0";
  }
  return $bit2 ;
}
////////////////////////////////////////////////////////////////////
function swingUp_by2($sUp){
  $init_by2 = " ";
  if ($sUp == false) {
    $init_by2 = "111";
  }
  else {
    $init_by2 = "000";
  }
  return $init_by2;
}
////////////////////////////////////////////////////////////////////
function swingDown_by3($sDown){
  $init_by3 = " ";
  $b3 = "";
  if ($sDown == false) {
    $b3 = "00000111";
  }
  else {
    $b3 = "00000000";
  }
  return $b3;
}
////////////////////////////////////////////////////////////////////
function speed($spd){
  $by5s = "";
  if ($spd == "AUTO") {
    $by5s = "00000101";
  }
  elseif ($spd == "1") {
    $by5s = "00000110";
  }
  elseif ($spd == "2") {
    $by5s = "00000010";
  }
  else {
    $by5s = "00000100";
  }
  return $by5s;
}
////////////////////////////////////////////////////////////////////
function mode_by7($mode){
	if ($mode == "cool") {
		$by7i = "00000100";
		return $by7i;
	} else {
		$by7i = "00000001";
		return $by7i;
	}
}
////////////////////////////////////////////////////////////////////
function mode_by10($mode){
	if ($mode == "cool") {
		$by10i = "00000100";
		return $by10i;
		}
	 else {
		$by10i = "00001100";
		return $by10i;
		}

}
////////////////////////////////////////////////////////////////////
function checkSum($by2, $by3, $by5, $by7, $by10,$by12){

  $by2i="";
  $by3i="";
  $by5i="";
  $by7i="";
  $by10i="";
  $by12i="";


	for ($i = 1; $i <= (strlen($by2)); $i++) {
	  $by2i .= substr($by2, (-$i),1);
	}

	for ($i = 1; $i <= (strlen($by3)); $i++) {
	  $by3i .= substr($by3, (-$i),1);
	}

	for ($i = 1; $i <= (strlen($by5)); $i++) {
	  $by5i .= substr($by5, (-$i),1);
	}

	for ($i = 1; $i <= (strlen($by7)); $i++) {
	  $by7i .= substr($by7, (-$i),1);
	}

	for ($i = 1; $i <= (strlen($by10)); $i++) {
	  $by10i .= substr($by10, (-$i),1);
	}

	for ($i = 1; $i <= (strlen($by12)); $i++) {
	  $by12i .= substr($by12, (-$i),1);
	}

	$decby2i = bindec($by2i);
	$decby3i = bindec($by3i);
	$decby5i = bindec($by5i);
	$decby7i = bindec($by7i);
	$decby10i = bindec($by10i);
	$decby12i = bindec($by12i);
	$checkSum1 = $decby2i + $decby3i + $decby5i + $decby7i + $decby10i + $decby12i;
  // echo $checkSum1;
  // echo "<br>";

	if ( $checkSum1 <= 318){
		$checkSum2 = $checkSum1 - 61;
	}
	elseif ($checkSum1 <= 570) {
		$checkSum2 = $checkSum1 - 317;
	}
	else{
		$checkSum2 = $checkSum1 - 573;
	}

	$byte13 = decbin($checkSum2);

  $bit13="";
	for ($i = 1; $i <= (strlen($byte13)); $i++) {
	  $bit13 .= substr($byte13, (-$i),1);
	}

	if ((strlen($bit13) == 5)){
	 	$bit13 = "$bit13" ."000";
	}

	if ((strlen($bit13) == 6)){
	 	$bit13 = "$bit13" ."00";
	}
  if ((strlen($bit13) == 7)){
	 	$bit13 = "$bit13" ."0";
	}

	return "$bit13";
}
////////////////////////////////////////////////////////////////////
$b2 = Temperatura($temp,$sUp);
$b3 = swingDown_by3($sDown);
$b5 = speed($spd);
$b7 = mode_by7($mode);
$b10 = mode_by10($mode);
$b13 = checkSum($b2, $b3, $b5, $b7, $b10, $b12);
////////////////////////////////////////////////////////////////////
$comando = "$b1" ."$b2" ."$b3" ."$b4" ."$b5" ."$b6"."$b7" ."$b8" ."$b9" ."$b10" ."$b11" ."$b12" ."$b13";
// echo "$comando";
// echo "<br>";
//// Codigo Carlos          ////
$messagearray=array('N_M' =>0 ,'order' => array(),'values'=> array(),'bits' => array());
//$bin="11000011111000100000011100000000000001010000000000000100000000000000000000000100000000001010000011110011";
$bin=$comando;
$length=strlen($bin);

// echo "__Numero de bits: ".$length."__";

$bytesmessage=round($length/32, 0);

// echo "__Numero de paquetes de 4 bytes: ".$bytesmessage."__";

$modBytemessage=$length-($bytesmessage*32);

// echo "__Numero de bits sobrantes: ".$modBytemessage."__";

// echo "__Juntar los paquetes de 4 bytes";
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


// print_r($messagearray);
// echo $spd;
// echo $temp ;
// echo $mode;
// echo $sUp;
// echo $sDown;
// echo $spd;


// echo $mode;
// echo $temp;
echo json_encode($messagearray);
// echo '{"N_M":4,"order":[1,2,3,4],"values":[195,3792109573,262144,41205],"bits":[8,32,32,32]}'

 ?>
