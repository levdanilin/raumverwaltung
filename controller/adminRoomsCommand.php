<?php
include("mapper/MySQLDatabase.php");
include("mapper/RoomDAO.php");
include("model/Room.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$meldung="";
$anlegen="";

$search='Raumsuche <a href="index.php?befehl=adminRoomSearch" class="button table">Suchen</a>';

$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a>";
if ($_SESSION['rights'] == "administrator") {
	$link .= "<a href='index.php?befehl=adminMenu' title='Home'> Startseite </a>";
	$anlegen='Neuen Raum anlegen <a href="index.php?befehl=newRoom" class="button table" > Anlegen</a>';
} elseif ($_SESSION['rights'] == "gast") {
	$meldung = '<h3 class="hallo"> Hallo ' ." ". $_SESSION['user'].  ' ! Sie sind als ' . $_SESSION['rights']. ' angemeldet. </h3> ';
} elseif ($_SESSION['rights'] == "employee") {
	$meldung = '<h3 class="hallo"> Hallo ' ." ". $_SESSION['user'].  ' ! Sie sind als ' . $_SESSION['rights']. ' angemeldet. </h3> ';
	$link .= "</a><a href='index.php?befehl=myBooking' > Meine Buchungen </a>";
}

$roomDAO = new RoomDAO();

$roomList = $roomDAO->readAll();


	include("views/adminRooms.php");
?>
