<?php
include('database.php');
  $query = "SELECT * FROM switch WHERE switch_user_id = '$user_id'";
  $result = mysqli_query($conn, $query);

  if(!$result) {
    die('Query Error' . mysqli_error($conn));
  }
$switch_status=0;
$template='';
$i=1;

  while($row = mysqli_fetch_array($result)) {
    $switch_status=$row['switch_status'];

    if ($switch_status==1) {
    $ch='checked';
    }
    else {
      $ch='';
    }

    $template .='
           <div class="col-xs-12 col-sm-6">
    <div class="box p-a">
      <div class="form-group row">
        <label class="col-sm-2 form-control-label">LED'.$i.'</label>
        <div class="col-sm-10">
          <label class="ui-switch ui-switch-md info m-t-xs">
            <input device_id="'.$row['switch_device_id'].'"num_led= "'.$i.'" id="'.$row['switch_id'].'"  type="checkbox" '.$ch.'>
            <i></i>
          </label>
        </div>
      </div>
    </div>
  </div>
          ';
          $i=$i+1;
  }

mysqli_close($conn);
?>