<?php
include('database.php');
  $switch_id=$_POST['id'];
  $switch_status=$_POST['status'];
  $query = "UPDATE switch SET switch_status=".$switch_status." WHERE  switch_id='".$switch_id."' AND switch_user_id='".$user_id."';";
  $result = mysqli_query($conn, $query);

  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }
echo $query;
mysqli_close($conn);
?>
