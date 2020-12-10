<?php
class RoomDAO {
  private $dbConnect;

  public function __construct() {
    $this->dbConnect = MySQLDatabase::getInstance();
  }

  public function create($number, $floor, $building, $personen, $art, $equipment) {
    $rid = -1;
    $query = "insert into room(number, floor, building, personen, art, equipment) values(?, ?, ?, ? , ?, ?)";
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("siiiss", $number, $floor, $building, $personen, $art, $equipment)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $rid = $preStmt->insert_id;
        }
      }
    }
  $preStmt->free_result();
  $preStmt->close();
  return $rid;
  }

  public function createLEV($number, $floor, $building, $personen, $art, $equipment) {
    $ergebnis = array();
    $rid = -1;
    $query = "insert into room(number, floor, building, personen, art, equipment) values(?, ?, ?, ? , ?, ?)";
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("siiiss", $number, $floor, $building, $personen, $art, $equipment)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $rid= $preStmt->insert_id;
          $ergebnis['rid'] = $rid;
          $timeperiodIds = array();
          $queryTimeperiodIds = "select tid from timeperiod";
          $timeperiodIds = $this->dbConnect->query($queryTimeperiodIds);
          $timeperiodIdsArray = $timeperiodIds->fetch_all(MYSQLI_ASSOC);
          for($i=1; $i<=count($timeperiodIdsArray); $i++) {
              $result = $this->dbConnect->query("insert into availables(rid, tid) values($rid, $i)");
              }
              if($result == true) {
                $ergebnis['insert'] = "inserted";
              }else{
                $ergebnis['insert'] = "not inserted";
          }
        }
      }
    }
  $preStmt->free_result();
  $preStmt->close();
  return $ergebnis;
  }

  public function getBuildingFloorLEV($rid) {
    $query = " select floor, building from room where rid = ?";
    $message = array();
    if(!$preStmt = $this->dbConnect->prepare($query)){
      $message['error'] = "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $rid)){
        $message['error'] = "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        $message['error'] = "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($floor, $building)){
          $message['error'] = "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
          $preStmt->fetch();
          $message['result']['floor'] = $floor;
          $message['result']['building'] = $building;
          $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $message;
  }

  public function getRoomObjectsLEV() {
    $query = "select rid, number, floor, building from room";
    $roomObjectsList = array();
    $roomObject = array();

    $sendQuery = $this->dbConnect->query($query);
    $roomObjectsList = $sendQuery->fetch_all(MYSQLI_ASSOC);

    $sendQuery->free();

    for($i=0; $i<count($roomObjectsList); $i++) {
      $roomObject[$i]['rid'] = $roomObjectsList[$i]['rid'];
      $roomObject[$i]['rnumber'] = $roomObjectsList[$i]['number'];
      $roomObject[$i]['building'] = $roomObjectsList[$i]['building'];
      $roomObject[$i]['floor'] = $roomObjectsList[$i]['floor'];
    }
    return $roomObject;
  }


  public function readLEV($rid) {
    $message = array();
    $query = "select rid, number, floor, building, equipment, personen, art from room where rid = ?";
    $room = null;

    if(!$preStmt = $this->dbConnect->prepare($query)){
      $message['error'] = "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $rid)){
        $message['error'] = "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        $message['error'] = "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($rid, $rnumber, $floor, $building, $equipment, $capacity, $type)){
            $message['error'] = "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()) {
              $message['result'] = new Room($rid, $rnumber, $floor, $building, $equipment, $capacity, $type);
            }

            $preStmt->free_result();
          }
        }
      }

      $preStmt->close();
    }
    return $message;
  }

  public function readAllAvailablesLEV() {
    $message = array();
    $query = "select r.rid, group_concat(concat(t.tid, '|', t.period), \"\" order by t.tid) as period from availables a join room r on r.rid = a.rid join timeperiod t on t.tid = a.tid group by r.rid order by r.number";
    $resultData = $this->dbConnect->query($query);
    $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);

    foreach ($resultArray as $key => $value) {
      $tmp = array();
      $periods = explode(",", $value['period']);

      foreach ($periods as $k => $v) {
        $details = explode("|", $v);
        $tmp['tid'] = $details[0];
        $tmp['period'] = $details[1];
        $message[$value['rid']][] = $tmp;
      }
    }
    $resultData->free();
    return $message;
    }

    public function readAllRoomnumbersAndIdsLEV() {
      $query = "select rid, number from room";
      $resultData = $this->dbConnect->query($query);
      $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);
      $resultData->free();
      return $resultArray;
    }

  public function read($rid) {
    $query = "select rid, number, floor, building, personen, art, equipment from room where rid = ?";
    $room = null;
    $rid = intval($rid);
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $rid)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($rid, $number, $floor, $building,  $personen, $art, $equipment)){
          echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()){
            $room = new Room($rid, $number, $floor, $building, $personen, $art, $equipment);
            }
            $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $room;
  }

  public function readALL()
  {
    $query = "SELECT rid, number, floor, building, personen, art, equipment from room ";
    $roomList = array();

    $resultData = $this->dbConnect->query($query);
    $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);

    for($i=0; $i < count($resultArray);  $i++)
    {
      $roomList[] = new Room($resultArray[$i]['rid'], $resultArray[$i]['number'], $resultArray[$i]['floor'],
                             $resultArray[$i]['building'],$resultArray[$i]['personen'],$resultArray[$i]['art'], $resultArray[$i]['equipment']);
    }

    $resultData->free();

    return $roomList;
  }


  public function readByNumberBuilding($number, $building)
  {
    $query = "SELECT rid, number, floor, building, personen,art, equipment from room  where number = ? and building = ? ";
    $room = null;
    $preStmt = $this->dbConnect->prepare($query);
    $preStmt->bind_param("si", $number, $building);
    $preStmt->execute();
    $preStmt->bind_result($rid, $number, $floor, $building, $personen, $art, $equipment);
    if($preStmt->fetch()){
      $room = new Room($rid, $number, $floor, $building, $personen, $art, $equipment);
    }
    $preStmt->free_result();
    $preStmt->close();

    return $room;

  }


public function updateRoom($roomnr, $floor, $building, $personen, $art, $equipment, $rid){
  $query=" update room set number=?, floor=?, building=?, personen=?, art=?, equipment=? where rid=?";

  if(!$preStmt = $this->dbConnect->prepare($query)){
    echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("siiissi", $roomnr, $floor, $building, $personen, $art, $equipment, $rid)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }
      }
    }
}
  public function delete($rid){

      $query=" delete from  room  where rid=?";

      if(!$preStmt = $this->dbConnect->prepare($query)){
        echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_param("i", $rid)){
            echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->execute()){
              echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }
          }
        }
  }


public function buchen($rid){
  $sql ="SELECT rid, number, floor, building, personen,art, equipment from room  where rid = ? ";
  $room = null;
  $preStmt = $this->dbConnect->prepare($sql);
  $preStmt->bind_param("i", $rid);
  $preStmt->execute();
  $preStmt->bind_result($rid, $number, $floor, $building, $personen, $art, $equipment);
  if($preStmt->fetch()){
    $room = new Room($rid, $number, $floor, $building, $personen, $art, $equipment);
  }

  $preStmt->free_result();
  $preStmt->close();
  return $room;

}

public function searchRoom($date,$tid) {
  $query = "select r.number, r.building  from room r
  where
  not r.rid in(select r.rid from room r, booking b , timeperiod t where r.rid=b.rid and b.tid=t.tid and b.date=? and  t.tid=? group by 1)
  order by 1";
  $roomList=array();

  $preStmt = $this->dbConnect->prepare($query);
  if(!$preStmt->prepare($query)){
    echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
  }else{
    if(!$preStmt->bind_param("si", $date,$tid)){
      echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->execute()){
      echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{

        $result  = $preStmt->get_result();
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);

        for($i=0; $i<count($resultArray);  $i++)
        {
          $roomList[] = array($resultArray[$i]['number'],$resultArray[$i]['building']);
        }
        $result->free();

      }
    }
  }

  return $roomList;
}
}
 ?>
