<?php
include("mapper/MySQLDatabase.php");
include("mapper/BookingDAO.php");
include("model/Booking.php");
include("mapper/RoomDAO.php");
include("model/Room.php");
include("mapper/TimeperiodDAO.php");
include("model/Timeperiod.php");
include("mapper/UserDAO.php");
include("model/User.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();


$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=userMenu' title='Home'> Startseite </a>";
$meldung='<h3 class="hallo"> Hallo ' ." ". $_SESSION['user'].  ' ! Sie sind als ' . $_SESSION['rights']. ' angemeldet. </h3> ';
$meldung1="";
$roomDAO= new RoomDAO();
$bookingDAO= new BookingDAO();


if(isset($_REQUEST['buchen']) && !empty($_REQUEST['roomnr']) && !empty($_REQUEST['building'])&& !empty($_REQUEST['date']) && !empty($_REQUEST['zeit'])){
	$roomnr = $_REQUEST['roomnr'];
	$building = $_REQUEST['building'];
	$date = $_REQUEST['date'];
	$period= $_REQUEST['zeit'];

	$room=$roomDAO->readByNumberBuilding($roomnr, $building);
	$rid=$room->getID();

	$booking=$bookingDAO->readByDateRoom($date,$period,$rid);

	if($room!==NULL){
		if($booking!==NULL){
			$meldung1=' Raum ist für diese Datum und Zeit gebucht. Bitte wählen Sie einen anderen Raum oder Zeit.';
			$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=myBooking' > Meine Buchungen </a>";
			include("views/userMenu.php");
		}
		elseif ($booking==NULL  &&  $period==4){
				$hourscheck=$bookingDAO->bookingHours($date,$rid);
					if($hourscheck ==true){
						$meldung1=" Sie können den Raum für den ganzen Tag nicht buchen!";
						include("views/userMenu.php");

					}
					else{
						$booking=$bookingDAO->create($date, $period, $rid, $_SESSION['userID']);
						$meldung1='Sie haben den Raum '.$roomnr." in Gebäude ". $building." am ". $date." gebucht";

						include("views/userMenu.php");
					}
		}
		elseif($booking==NULL  && $period!==4){
				$tagcheck=$bookingDAO->bookingTag($date,$rid);
					if($tagcheck!==NULL){
						$meldung1="Leider ist der Raum für den ganzen Tag gebucht. Bitte Wählen Sie andere Raum oder Tag !";

						include("views/userMenu.php");
					}else{
						$booking=$bookingDAO->create($date, $period, $rid, $_SESSION['userID']);
						$meldung1='Sie haben den Raum '.$roomnr." in Gebäude ". $building." am ". $date." gebucht";

						include("views/userMenu.php");
					}
		}
	}

}		elseif(isset($_REQUEST['buchen']) && !empty($_REQUEST['roomnr']) && !empty($_REQUEST['building'])&& empty($_REQUEST['date']) && !empty($_REQUEST['zeit'])){
		$roomnr = $_REQUEST['roomnr'];
		$building = $_REQUEST['building'];
		$date = $_REQUEST['date'];
		$period= $_REQUEST['zeit'];
		$roomnr = $_REQUEST['roomnr'];
		$building = $_REQUEST['building'];
	$meldung='Bitte geben Sie ein Datum ein oder gehen Sie zurück </a> ';
	include("views/userRoomBooking.php");
	}

elseif(isset($_POST['abbrechen'])){


	$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=myBooking' > Meine Buchungen </a>";
	include("views/userMenu.php");
}
?>
