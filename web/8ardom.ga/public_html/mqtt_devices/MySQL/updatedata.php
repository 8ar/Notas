<?php
include('database.php');

if(!empty($search)) {
  $query = "SELECT * FROM switch WHERE switch_user_id = '$user_id'";
  $result = mysqli_query($conn, $query);

  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }

  $json = array();
  while($row = mysqli_fetch_array($result)) {
    $json[] = array(
      'id' => $row['id']
    );
  }
  $jsonstring = json_encode($json);
  echo $jsonstring;
}
?>
