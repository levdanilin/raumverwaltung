<?php
include_once("../mapper/MySQLDatabase.php");
include("../mapper/RoomDAO.php");
include("../model/Room.php");
include("../mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$roomDAO = new RoomDao();
$allAvailables = $roomDAO->readAllAvailablesLEV();
$all = $roomDAO->readAllRoomnumbersAndIdsLEV();
$role = $_SESSION['rights'];


$result['allAvailables'] = $allAvailables;
$result['roomNumbersAndIds'] = $all;
$result['role'] = $role;

header('Content-Type: application/json');
echo json_encode($result);
?>
