<?php
include_once("../mapper/MySQLDatabase.php");
include("../mapper/BookingDAO.php");
include("../model/Booking.php");
$bookingDAO = new BookingDAO();

if (isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['rid'])) {
  $startDate = $_GET['startDate'];
  $endDate = $_GET['endDate'];
  $rid = $_GET['rid'];

  $bookings = $bookingDAO->getBookingsByParamsLEV($startDate, $endDate, $rid);

  header('Content-Type: application/json');
  echo json_encode($bookings);
}

?>
