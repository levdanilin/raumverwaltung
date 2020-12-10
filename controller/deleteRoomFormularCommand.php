<?php
include("mapper/MySQLDatabase.php");
include("mapper/RoomDAO.php");
include("model/Room.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$meldung="";
$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu' title='Home'> Startseite </a><a href='index.php?befehl=adminRooms'> Zurück </a>";
$roomDAO= new RoomDAO();

if (isset($_REQUEST['rid']) && isset($_REQUEST['roomnr']) && isset($_REQUEST['floor']) && isset($_REQUEST['building']) && isset($_REQUEST['personen']) && isset($_REQUEST['art']) && isset($_REQUEST['equipment'])){
        $rid=intval($_REQUEST['rid']); 
        $roomnr=$_REQUEST['roomnr'];
        $floor=intval($_REQUEST['floor']);  
        $building=intval($_REQUEST['building']); 
        $personen=intval($_REQUEST['personen']);
        $art=($_REQUEST['art']);   
        $equipment=$_REQUEST['equipment'];
               
}else{
        
        include("views/deleteRoom.php");
}

include("views/deleteRoom.php");

?> 
