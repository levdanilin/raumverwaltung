<?php
include("mapper/MySQLDatabase.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$meldung="";

if ($_SESSION['rights'] == "administrator") {
  $link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu' title='Home'> Startseite </a><a href='index.php?befehl=allBooking'> Zurück </a>";
} elseif ($_SESSION['rights'] == "employee") {
  $link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminRooms' title='Home'> Startseite </a><a href='index.php?befehl=myBooking'> Zurück </a>";
}

if (isset($_REQUEST['roomnr']) && isset($_REQUEST['building']) && isset($_REQUEST['bid']) && isset($_REQUEST['date']) && isset($_REQUEST['zeit'])){
    $bid=($_REQUEST['bid']);
    $roomnr=($_REQUEST['roomnr']);
    $building=($_REQUEST['building']);
    $date=($_REQUEST['date']);
    $zeit=($_REQUEST['zeit']);
    echo $bid;

}else{

}

include("views/deleteBooking.php");


?>
