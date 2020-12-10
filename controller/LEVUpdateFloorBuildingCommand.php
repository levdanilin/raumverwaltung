<?php
include_once("../mapper/MySQLDatabase.php");
include("../mapper/RoomDAO.php");
include("../model/Room.php");
$roomDAO = new RoomDAO();

if(isset($_GET['rid'])) {
  $rid = $_GET['rid'];
  $message = $roomDAO->getBuildingFloorLEV($rid);
  header('Content-Type: application/json');
  echo json_encode($message);
}



?>
