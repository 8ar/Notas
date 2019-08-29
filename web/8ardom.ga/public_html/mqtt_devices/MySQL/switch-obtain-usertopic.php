<?php
include('database.php');
  $query = "SELECT username, topic FROM mqtt_acl WHERE clientid=".$user_id." LIMIT 1;";
  $result = mysqli_query($conn, $query);

  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }

while($row = mysqli_fetch_array($result)) {
$username=$row['username'];
$topic=$row['topic'];
}
$query = "SELECT password FROM mqtt_user WHERE userid=".$user_id." LIMIT 1;";
$result = mysqli_query($conn, $query);

if(!$result) {
  die('Query Error' . mysqli_error($conn));
}

while($row = mysqli_fetch_array($result)) {
$password=$row['password'];
}

$json = array();
$json[] = array(
  'username' =>$username,
  'topic' =>$topic,
  'password'=>$password
               );


$jsonstring = json_encode($json);
echo $jsonstring;
mysqli_close($conn);
?>
