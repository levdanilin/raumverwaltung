<?php
include("mapper/MySQLDatabase.php");
include("mapper/UserDAO.php");
include("model/User.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$meldung="";
$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu'> Startseie </a><a href='index.php?befehl=allUsers'> Zurück </a> ";
$userDAO= new UserDAO();

if (isset($_REQUEST['uid']) && isset($_REQUEST['name']) && isset($_REQUEST['secondname']) && isset($_REQUEST['email'])){
        $uid=intval($_REQUEST['uid']); 
        $name=$_REQUEST['name'];
        $secondname=$_REQUEST['secondname'];
        $email=$_REQUEST['email'];  
        include("views/userUpdate.php");   
             
}else{
        include("views/adminMenu.php");
}



?> 