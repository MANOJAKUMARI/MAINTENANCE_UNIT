<?php
$conn = mysqli_connect('localhost', 'root', '', 'university_equipment_maintenance');

if($conn == false){
  echo 'Connection error: ' . mysqli_connect_error();
}
?>