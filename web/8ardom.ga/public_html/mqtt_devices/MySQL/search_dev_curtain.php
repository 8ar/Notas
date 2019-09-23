<?php
include('database.php');
$query = "SELECT * FROM devices WHERE devices_user_id = '$user_id' AND devices_id LIKE '00003%';";
  //   WHERE devices_user_id = '$user_id' AND devices_id LIKE '00001%'";
  $result = mysqli_query($conn, $query);
  $models = $result->fetch_all(MYSQLI_ASSOC);
  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }
mysqli_close($conn);
?>
