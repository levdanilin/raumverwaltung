
<?php	
include("mapper/MySQLDatabase.php");
include("mapper/UserDAO.php");
include("model/User.php");
include("mapper/RoleDAO.php");
include("model/Role.php");
include("mapper/LoginCheck.php");
LoginCheck::activitätCheck();

$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu'> Startseie </a>";
$meldung = "";
$meldung1="";
$user="";
$userList="";
$delete="";

$userDAO = new UserDAO();


if(isset($_REQUEST['ballusers'])){
$userList = $userDAO->readAll();
$link .= "<a href='index.php?befehl=userManage'> Zurück </a> ";
include("views/allUsers.php");
}
    elseif(isset($_REQUEST['bsearch']) && !empty($_REQUEST['choose']) && !empty($_REQUEST['search'])){
          
            if($_REQUEST['choose']=="email"){
                $email=$_REQUEST['search'];
                $user=$userDAO->readbyEmail($email);
                if($user==NULL){
                    $user="Der Benutzer ist nicht in Datenbank!";
                    include("views/userManage.php");
                }else{
                    $user=$user; 
                    include("views/userManage.php");  
                }
            }
                 
                elseif($_REQUEST['choose']=="uid"){
                    $uid=$_REQUEST['search'];
                    $user=$userDAO->readbyUID($uid);
                    if($user==NULL){
                        $user="Der Benutzer ist nicht in Datenbank!";
                        include("views/userManage.php");
                    }else{
                        $user=$user; 
                        include("views/userManage.php");  
                    }
                }
                 elseif($_REQUEST['choose']=="name"){
                        $name=$_REQUEST['search'];
                        $user=$userDAO->readbyName($name);
                        if($user==NULL){
                            $user="Der Benutzer ist nicht in Datenbank!";
                            include("views/userManage.php");
                        }else{
                            $user=$user; 
                            include("views/userManage.php");  
                        }
                }
                   
                        elseif($_REQUEST['choose']=="secondname"){
                            $secondname=$_REQUEST['search'];
                            $user=$userDAO->readbySecondname($secondname);
                            
                            if($user==NULL){
                                $user="Der Benutzer ist nicht in Datenbank!";
                                include("views/userManage.php");
                            }else{
                                $user=$user; 
                                include("views/userManage.php");  
                            }
                    }
                        
                          
        }    
    
    /* elseif(isset($_REQUEST['brole']) && !empty ($_REQUEST['role'])){
        $role_id=$_REQUEST['role'];
        $userList=$userDAO->readAllbyRole($role_id); */
        /* $x="";
		    echo '<table >
							<tr>
								<th>RaumNr</th>
								<th>Gebäude</th>
								<th>Etage</th>
								<th>Personen</th>
								<th>Art</th>
								<th>Ausstatung</th>
								<th</th>
								<th></th>
								<th></th>
							</tr>';
							$roomList = $roomDAO->readAll();				
			for($i=0; $i < count($roomList); $i++){ 
				$x=($x=="#ccc")?"#eee":"#ccc";
					echo '<tr style="background-color: '.$x.'; ">
					
						   <td>'.$roomList[$i]->getNumber().'</td>
						  <td>'.$roomList[$i]->getBuilding().'</td>
						  <td>'.$roomList[$i]->getFloor().'</td>					
						  <td>'.$roomList[$i]->getPersonen().'</td>
						  <td>'.$roomList[$i]->getArt().'</td>
						  <td>'.$roomList[$i]->getEquipment().'</td>
						   <td><a href="index.php?befehl=bookingFormular&roomnr='.$roomList[$i]->getNumber().'&building='.$roomList[$i]->getBuilding().'" class=" button table" > Buchen</a></td>
						   <td><a href="index.php?befehl=modifyRoom&rid='.$roomList[$i]->getID().'&roomnr='.$roomList[$i]->getNumber().'&floor='.$roomList[$i]->getFloor().'&building='.$roomList[$i]->getBuilding().'&personen='.$roomList[$i]->getPersonen().'&art='.$roomList[$i]->getArt().'&equipment='.$roomList[$i]->getEquipment().'" class="button table"> Ändern</a></td>
						   <td><a href="index.php?befehl=" class="button table" > Löschen</a></td>
						   </tr>';
			}		
				echo "</table>"
        include("views/userManage.php"); */
      
    elseif(empty($_REQUEST['search'])){
        $meldung1="Bitte fühelen Sie alle Felder aus";
        //$link = "<a href='index.php?befehl=logout' title='Logout' > Logout </a><a href='index.php?befehl=adminMenu'> Startseite </a> ";
        include("views/userManage.php"); 
        }    
        else
        {
            $user="Fehler!";
            include("views/userManage.php");
            }                  

?>
