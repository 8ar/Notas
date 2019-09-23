<?php
$temp = "16";
$temp = "20";
$mode = "cool";

if (isset($_POST["temperature"]) && isset($_POST["mode"])) {
  $temp = $_POST["temperature"];
  $mode=$_POST["mode"];
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



function Temperatura($tempe) {
 //    echo "$tempe<br>";
 //    echo "El numero en decimal que busco es: " .($tempe - 8) ;
 //    echo "<br>";
    $binario = decbin($tempe - 8);
 //    echo ' y en binario es: '.$binario;
	// echo "<br>";
  $bit="";
	for ($i = 1; $i <= (strlen($binario)); $i++) {
	  $bit .= substr($binario, (-$i),1);
	}

	$bit2 = "111$bit";

	 if ((strlen($bit2) == 7)){
	 	$bit2 = "111$bit" ."0";
	 }
	return $bit2 ;
}

function mode_by7($mode){
	if ($mode == "cool") {
		$by7i = "00000100";
		//echo $by7i;
		//echo "<br>";
		return $by7i;
	} else {
		$by7i = "00000001";
		//echo $by7i;
		//echo "<br>";
		return $by7i;
	}
}

function mode_by10($mode){
	if ($mode == "cool") {
		$by10i = "00000100";
		//echo $by10i;
		//echo "<br>";
		return $by10i;
		}

	 else {
		$by10i = "00001100";
		//echo $by10i;
		//echo "<br>";
		return $by10i;
		}

}

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
	// echo "$decby2i";
	// echo "<br>";
	// echo "$decby3i";
	// echo "<br>";
	// echo "$decby5i";
	// echo "<br>";
	// echo "$decby7i";
	// echo "<br>";
	// echo "$decby10i";
	// echo "<br>";
	// echo "$decby12i";
	// echo "<br>";
	// echo "$checkSum1";
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
	// echo "$checkSum2";
	// echo "<br>";
	$byte13 = decbin($checkSum2);
	// echo "$byte13";
	// echo "<br>";
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

	return "$bit13";
}

$b2 = Temperatura($temp);
$b7 = mode_by7($mode);
$b10 = mode_by10($mode);
//echo "$b2";
//$b12 = "10000000" ;
//echo "<br>";
$b13 = checkSum($b2, $b3, $b5, $b7, $b10, $b12);
//echo "$b13";

$comando = "$b1" ."$b2" ."$b3" ."$b4" ."$b5" ."$b6"."$b7" ."$b8" ."$b9" ."$b10" ."$b11" ."$b12" ."$b13";

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
// echo $mode;
// echo $temp;
echo json_encode($messagearray);
// echo '{"N_M":4,"order":[1,2,3,4],"values":[195,3792109573,262144,41205],"bits":[8,32,32,32]}'

 ?>
