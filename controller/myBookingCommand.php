<?php
include("mapper/MySQLDatabase.php");
include("mapper/BookingDAO.php");
include("model/Booking.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$meldung="";
$anlegen="";
$search="";
$link = "<a href='index.php?befehl=logout'> Logout </a><a href='index.php?befehl=adminRooms' title='Logout' > Zurück </a>
";

$bookingDAO=new BookingDAO();
$uid=$_SESSION['userID'];
$list1 = $bookingDAO-> bookingUser($uid);

	include("views/myBooking.php");
?>
