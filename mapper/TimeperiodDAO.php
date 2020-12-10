<?php

class TimeperiodDAO {
  private $dbConnect;

  public function __construct() {
    $this->dbConnect = MySQLDatabase::getInstance();
  }

  public function create($period) {
    $tid = -1;
    $query = "INSERT into timeperiod(period) values(?)";
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("s", $period)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $tid = $preStmt->insert_id;
        }
      }
    }
  $preStmt->free_result();
  $preStmt->close();
  return $tid;
  }

  public function read($tid) {
    $query = "SELECT tid,period FROM  timeperiod where tid = ?";
    $timeperiod = null;

    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $tid)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($tid, $period)){
          echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()){
            $timeperiod = new Timeperiod($tid, $period);
            }
            $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $timeperiod;
  }

  public function readALL()
  {
    $query = "SELECT tid, period FROM timeperiod ";
    $timeperiodList = array();
    
    $resultData = $this->dbConnect->query($query);
    $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);
    
    for($i=0; $i < count($resultArray); $ $i++) 
    {
      $roomList[] = new Room($resultArray[$i]['tid'], $resultArray[$i]['period']);
    }

    $resultData->free();
    return $timeperiodList;
  }

  public function updatePeriod($period,$tid){
    $query=" update timeperiod set period=? where tid=?";
  
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->bind_param("si", $period, $tid)){
          echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->execute()){
            echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_result($tid, $period)){
              echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if($preStmt->fetch()){
                $timeperiod = new Timeperiod($tid, $position);
              }
              $preStmt->free_result();
            }
          }
        }
        $preStmt->close();
      }
      return $timeperiod;
    }
  
    public function delete($tid){
  
        $query=" delete from timeperiod  where tid=?";
      
        if(!$preStmt = $this->dbConnect->prepare($query)){
          echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_param("i", $tid)){
              echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if(!$preStmt->execute()){
                echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
              }
            }
          }
    }


    public function readByPeriod($period){
      $query=" select tid, period  from timeperiod where period=? ";
     $timeperiod=NULL;
      $preStmt = $this->dbConnect->prepare($query);
      $preStmt->bind_param("s", $period);
      $preStmt->execute();
      $preStmt->bind_result($tid, $period);
      if($preStmt->fetch()){
           $timeperiod = new Timeperiod($tid, $period);
                }
      $preStmt->free_result();
      $preStmt->close();
     
      return $timeperiod;
    }
  }
?>