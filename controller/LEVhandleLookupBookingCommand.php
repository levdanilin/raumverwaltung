<?php
session_start();
include_once("../mapper/MySQLDatabase.php");
include("../mapper/BookingDAO.php");
include("../mapper/UserDAO.php");
include("../model/Booking.php");
include("../model/User.php");
$bookingDAO = new BookingDAO();
$userDAO = new UserDAO();
$message = array();

if (isset($_GET['rid']) && !empty($_SESSION['userID'])) {
  $rid = $_GET['rid'];
  $date = $_GET['date'];
  $tid = $_GET['tid'];
  $uid = $_SESSION['userID'];

  $newBooking = $bookingDAO->createLEV($date, $tid, $rid, $uid);
  $user = $userDAO->readbyUIDLEV($uid);
  $message['booking'] = $newBooking;
  $message['user']['name'] = $user['result']->getName();
  $message['user']['secondname'] = $user['result']->getSecondname();

  header('Content-Type: application/json');
  echo json_encode($message);
}

?>
