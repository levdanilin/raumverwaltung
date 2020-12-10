<?php
include_once("../mapper/MySQLDatabase.php");
include("../mapper/RoomDAO.php");
include("../model/Room.php");
include("../mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$roomDAO = new RoomDAO();

if(isset($_GET["id"])) {
  $rid = $_GET["id"];
  $room=$roomDAO->readLEV($rid);
  $roomObjects = $roomDAO->getRoomObjectsLEV();
  $role = $_SESSION['rights'];

  $resultData['room'] = array();
  $resultData['room']['rid'] = $room['result']->getID();
  $resultData['room']['rnumber'] = $room['result']->getNumber();
  $resultData['room']['floor'] = $room['result']->getFloor();
  $resultData['room']['building'] = $room['result']->getBuilding();
  $resultData['roomObjects'] = $roomObjects;
  $resultData['role'] = $role;

  header('Content-Type: application/json');
  echo json_encode($resultData);
}

?>
