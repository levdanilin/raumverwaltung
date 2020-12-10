<?php

class BookingDAO {
  private $dbConnect;

  public function __construct() {
    $this->dbConnect = MySQLDatabase::getInstance();
  }

  public function create($date, $timeperiod, $room, $user) {
    $bid = -1;
    $query = "insert into booking(date, tid, rid, uid) values(?, ?, ?, ? )";
    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("siii",$date, $timeperiod, $room, $user )){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $bid = $preStmt->insert_id;
        }
      }
    }
  $preStmt->free_result();
  $preStmt->close();
  return $bid;
  }

  public function createLEV($date, $timeperiod, $room, $user) {
    $message = array();
    $bid = -1;
    $query = "insert into booking(date, tid, rid, uid) values(?, ?, ?, ? )";

    if(!$preStmt = $this->dbConnect->prepare($query)){
      $message["error"] = "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("ssii", $date, $timeperiod, $room, $user )){
        $message["error"] = "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          $message["error"] = "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          $message["id"] = $preStmt->insert_id;
          $message['rid'] = $room;
          $message['status'] = 'success';
        }
      }
    }

    $preStmt->free_result();
    $preStmt->close();
    return $message;
  }

  function getBookingsByParamsLEV($startDate, $endDate, $rid) {
    $message = array();

    if (!empty($startDate) && !empty($endDate) && !empty($rid)) {
      $query = "select b.bid, b.date, b.rid, r.number, t.tid, u.name, u.secondname from booking b join room r on r.rid = b.rid join timeperiod t on t.tid = b.tid join user u on u.uid = b.uid where b.date between ? and ? and b.rid = ?";
      $preStmt = $this->dbConnect->prepare($query);
      $preStmt->bind_param("ssi", $startDate, $endDate, $rid);
      $preStmt->execute();
      $result = $preStmt->get_result();
    } else if (!empty($startDate) && !empty($endDate)) {
      $query = "select b.bid, b.date, b.rid, r.number, t.tid, u.name, u.secondname from booking b join room r on r.rid = b.rid join timeperiod t on t.tid = b.tid join user u on u.uid = b.uid where b.date between ? and ?";
      $preStmt = $this->dbConnect->prepare($query);
      $preStmt->bind_param("ss", $startDate, $endDate);
      $preStmt->execute();
      $result = $preStmt->get_result();
    } else if (!empty($startDate) && empty($rid)) {
      $query = "select b.bid, b.date, b.rid, r.number, t.tid, u.name, u.secondname from booking b join room r on r.rid = b.rid join timeperiod t on t.tid = b.tid join user u on u.uid = b.uid where b.date = ?";
      $preStmt = $this->dbConnect->prepare($query);
      $preStmt->bind_param("s", $startDate);
      $preStmt->execute();
      $result = $preStmt->get_result();
    } else if (!empty($endDate) && !empty($rid)) {
      $message['error'] = 'Enddatum kann ohne Startdatum nicht gewählt werden!';
    } else if (!empty($endDate)) {
      $message['error'] = 'Enddatum kann ohne Startdatum nicht gewählt werden!';
    } else if (!empty($startDate) && !empty($rid)) {
      $query = "select b.bid, b.date, b.rid, r.number, t.tid, u.name, u.secondname from booking b join room r on r.rid = b.rid join timeperiod t on t.tid = b.tid join user u on u.uid = b.uid where b.date = ? and b.rid = ?";
      $preStmt = $this->dbConnect->prepare($query);
      $preStmt->bind_param("si", $startDate, $rid);
      $preStmt->execute();
      $result = $preStmt->get_result();
    } else if (!empty($rid)) {
      $message['error'] = 'Bitte wählen Sie ein Datum!';
    } else {
      $message['error'] = 'Bitte wählen Sie mindestens ein Startdatum und eine Raumnummer!';
    }

    if (isset($result) && !empty($result)) {
      while ($data = $result->fetch_assoc()) {
        $tmp = array();
        $tmp["id"] = $data["bid"];
        $tmp["date"] = $data["date"];
        $tmp["rnumber"] = $data["number"];
        $tmp["tid"] = $data["tid"];
        $tmp["name"] = $data["name"];
        $tmp["secondname"] = $data["secondname"];
        $tmp["rid"] = $data["rid"];
        $message[] = $tmp;
      }

      $preStmt->free_result();
      $preStmt->close();
    }

    return $message;
  }

  public function read($bid) {
    $query = "select  b.bid, b.date, b.tid, b.rid, b.uid from booking b, timeperiod t, room r, user u  where u.uid=b.uid AND b.tid=t.tid AND r.rid=b.rid AND b.bid = ?";
    $room = null;

    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if(!$preStmt->bind_param("i", $bid)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
        echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->bind_result($bid, $date, $timeperiod, $room, $user)){
          echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if($preStmt->fetch()){
            $room = new Room($bid, $date, $timeperiod, $room, $user);
            }
            $preStmt->free_result();
          }
        }
      }
      $preStmt->close();
    }
    return $booking;
  }

  /* public function readALL()
  {
    $query = "SELECT bid, date, tid, rid, uid from booking";
    $bookingList = array();

    $result= $this->dbConnect->query($query);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    for($i=0; $i<count($resultArray); $  $i++)
    {
      $bookingList[] = new Booking($resultArray[$i]['bid'], $resultArray[$i]['date'], $resultArray[$i]['tid'],
                             $resultArray[$i]['rid'], $resultArray[$i]['uid']);
    }

    $resultData->free_result();
    return $bookingList;
  } */


  public function readByDateRoom($date,$tid,$rid)
  {
    $query = "SELECT b.bid, b.date, b.tid,b.rid, b.uid from booking b, timeperiod t, room r , user u where b.tid=t.tid and b.rid=r.rid and b.uid=u.uid and b.date = ? and b.tid=? and b.rid = ? ";
    $booking = null;
    $preStmt = $this->dbConnect->prepare($query);
    $preStmt->bind_param("sii", $date, $tid,$rid);
    $preStmt->execute();
    $preStmt->bind_result($bid, $date, $tid, $rid, $uid);
    if($preStmt->fetch()){
         $booking = new Booking($bid, $date, $tid,$rid, $uid);
                        }
    $preStmt->free_result();
    $preStmt->close();
    return $booking;
  }


  public function updateDate($date,$bid){
  $query=" update booking set date=? where bid=?";

  if(!$preStmt = $this->dbConnect->prepare($query)){
    echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
    }else{
      if($preStmt->bind_param("si", $date, $bid)){
        echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{

        }
      }
      $preStmt->close();
    }

  }

  public function searchDateBooking($date){
    $sql = " SELECT b.bid,u.secondname,r.number,r.building,b.date, t.period,r.personen, r.art, r.equipment from booking b, room r, timeperiod t, user u where b.uid=u.uid and b.tid=t.tid and r.rid=b.rid and b.date=? order by 6";
    $List = array();

    $preStmt = $this->dbConnect->prepare($sql);
      if(!$preStmt->prepare($sql)){
        echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->bind_param("s",$date)){
          echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{

            $result  = $preStmt->get_result();
            $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    for($i=0; $i<count($resultArray);  $i++)
    {
      $List[] = array($resultArray[$i]['bid'], $resultArray[$i]['secondname'], $resultArray[$i]['number'],
                             $resultArray[$i]['building'],$resultArray[$i]['date'],$resultArray[$i]['period'],$resultArray[$i]['personen'],$resultArray[$i]['art'], $resultArray[$i]['equipment']);
    }

    $result->free();

          }
        }
      }
    return $List;

    }


  public function delete($bid){
    $query = "delete from booking where bid=?";

    if(!$preStmt = $this->dbConnect->prepare($query)){
      echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->bind_param("i", $bid)){
          echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->execute()){
            echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }
        }
      }
}


  public function checkBooking($date,$tid, $rid){
		$sql = " SELECT bid, date, tid,rid,uid FROM booking WHERE $date = ? and $tid=? and $rid=? ";

		$booking = null;

		$preStmt = $this->dbConnect->prepare($sql);
		$preStmt->bind_param("sii", $date, $tid, $rid);
		$preStmt->execute();
		$preStmt->bind_result( $bid,$date, $tid, $rid ,$uid);
		if($preStmt->fetch()){
							$booking = new Booking( $bid,$date, $tid, $rid ,$uid);
						}
						$preStmt->free_result();

			$preStmt->close();

		return $booking;
  }
  public function bookingUser($uid){

	$sql = " SELECT b.bid,r.number,r.building,b.date, t.period,r.personen, r.art, r.equipment from booking b, room r, timeperiod t, user u where b.uid=u.uid and b.tid=t.tid and r.rid=b.rid and b.uid=? order by 1 desc";
    $List = array();

    $preStmt = $this->dbConnect->prepare($sql);
      if(!$preStmt->prepare($sql)){
        echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
      }else{
        if(!$preStmt->bind_param("i",$uid)){
          echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
        }else{
          if(!$preStmt->execute()){
          echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{

            $result  = $preStmt->get_result();
            $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    for($i=0; $i<count($resultArray);  $i++)
    {
      $List[] = array($resultArray[$i]['bid'], $resultArray[$i]['number'],
                             $resultArray[$i]['building'],$resultArray[$i]['date'],$resultArray[$i]['period'],$resultArray[$i]['personen'],$resultArray[$i]['art'], $resultArray[$i]['equipment']);
    }

    $result->free();

          }
        }
      }
    return $List;

    }

    public function bookingAll(){

      $sql = " SELECT b.bid,u.secondname,r.number,r.building,b.date, t.period,r.personen, r.art, r.equipment from booking b, room r, timeperiod t, user u where b.uid=u.uid and b.tid=t.tid and r.rid=b.rid and b.date > (CURRENT_DATE-1) order by 5 ,3";
        $List = array();

        $resultData = $this->dbConnect->query($sql);
        $resultArray = $resultData->fetch_all(MYSQLI_ASSOC);

        for($i=0; $i<count($resultArray);  $i++)
        {
          $List[] = array($resultArray[$i]['bid'],$resultArray[$i]['secondname'], $resultArray[$i]['number'],
                                 $resultArray[$i]['building'],$resultArray[$i]['date'],$resultArray[$i]['period'],$resultArray[$i]['personen'],$resultArray[$i]['art'], $resultArray[$i]['equipment']);
        }

        $resultData->free();

        return $List;

        }

        public function bookingTag($date,$rid) {
          $query = "select * from booking where tid=4 and date=? and rid=? ";
          $booking = null;

          if(!$preStmt = $this->dbConnect->prepare($query)){
            echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_param("si", $date,$rid)){
              echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if(!$preStmt->execute()){
              echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
              }else{
                if(!$preStmt->bind_result($bid, $date, $tid, $rid, $uid)){
                echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
                }else{
                  if($preStmt->fetch()){
                  $booking= new Booking($bid, $date, $tid,$rid,$uid);
                  }
                  $preStmt->free_result();
                }
              }
            }
            $preStmt->close();
          }
          return $booking;
        }

        public function bookingHours($date,$rid) {
          $query = "select * from booking where date=? and rid=? ";
          $found=false;

          if(!$preStmt = $this->dbConnect->prepare($query)){
            echo "Fehler by 'prepare' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
          }else{
            if(!$preStmt->bind_param("si", $date,$rid)){
              echo "Fehler by 'bind_param' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
            }else{
              if(!$preStmt->execute()){
              echo "Fehler by 'execute' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
              }else{
                if(!$preStmt->bind_result($bid, $date, $tid, $rid, $uid )){
                echo "Fehler by 'bind_result' (" . $this->dbConnect->errno . ")" . $this->dbConnect->error . "<br>";
                }else{
                  if($preStmt->store_result()){
                    if($preStmt->num_rows >0){
                      $found =true;
                    }
                  }
                  $preStmt->free_result();
                }
              }
            }
            $preStmt->close();
          }
          return $found;
        }

}

?>
