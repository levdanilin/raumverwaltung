<?php
include("mapper/MySQLDatabase.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();


$meldung="";
$link="";


if (isset($_REQUEST['roomnr']) && isset($_REQUEST['building'])){
        $roomnr=$_REQUEST['roomnr'];
        $building=intval($_REQUEST['building']);  
        if ( $_SESSION['rights']=="Benutzer"){
                $link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=userMenu'> Startseite </a> ";
                include("views/userRoomBooking.php");    
        }else{
                $link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu' title='Home'> Startseite </a><a href='index.php?befehl=adminRooms'> Zurück </a> ";
                include("views/roomBooking.php");
        }

}else{
        $building="";
        $roomnr="";
        header("location : index.php?befehl=logout");
        exit;
}

?> 
