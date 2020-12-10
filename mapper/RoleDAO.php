<?php

class RoleDAO {
  private $dbConnect;

  public function __construct() {
    $this->dbConnect = MySQLDatabase::getInstance();
  }

  public function create($position) {
    $role_id = -1;
    $query = "INSERT into role(position) VALUES (?)";
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("s",$position)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $role_id = $preStmt->insert_id;
        }
      }
    }
  $preStmt->free_result();
  $preStmt->close();
  return $role_id;
  }

  public function read($role_id) {
    $query = "SELECT  role_id, position FROM role WHERE role_id = ?";
    $role= null;

    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $role_id)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($role_id, $position)){
          echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()){
            $role = new Role($role_id, $position);
            }
            $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $role;
  }


  public function readPosition($role_id) {
    $query = "SELECT  position FROM role WHERE role_id = ?";
    $position= null;

    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $role_id)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($position)){
          echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()){
            $position = $position;
            }
            $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $position;
  }

  public function readALL()
  {
    $query = "SELECT role_id, position from role ";
    $roleList = array();
    
    $resultData = $this->dbConnect->query($query);
    $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);
    
    for($i=0; $i < count($resultArray); $  $i++) 
    {
      $roleList[] = new Role($resultArray[$i]['role_id'], $resultArray[$i]['position']);
    }

    $resultData->free();
    return $roleList;
  }

  public function updateRole($position,$role_id){
    $query=" update role set position=? where role_id=?";
  
    if(!$preStmt = $dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->bind_param("si", $position, $role_id)){
          echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->execute()){
            echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_result($bid, $date, $tid, $rid, $uid)){
              echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if($preStmt->fetch()){
                $user = new User($role_id, $position);
              }
              $preStmt->free_result();
            }
          }
        }
        $preStmt->close();
      }
      return $role;
    }
  
    public function deleteRole($role_id){
  
        $query=" delete from role  where role_id=?";
      
        if(!$preStmt = $this->dbConnect->prepare($query)){
          echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_param("i", $role_id)){
              echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if(!$preStmt->execute()){
                echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
              }
            }
        }
  }
}

 ?>
