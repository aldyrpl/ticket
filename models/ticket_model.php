<?php

function addTicket($event_id, $total)
{
  //Check if event exist
  if (checkEventIsExist($event_id)) {
    //Looping by the total ticket detail we want to add
    $x = 0;
    while ($x < $total) {
      try {
        //Connect to DB
        $conn = connect();

        // Generate ticket code
        $code = generateTicketCode();

        //Preparing SQL Statement
        $stmt = $conn->prepare("INSERT INTO ticket (event_id, ticket_code, created_at) 
        VALUES (:event_id, :ticket_code, :created_at)");

        //Bind param to SQL Statement
        $stmt->bindParam(':event_id', htmlspecialchars(strip_tags($event_id)));
        $stmt->bindParam(':ticket_code', htmlspecialchars(strip_tags($code)));
        $stmt->bindParam(':created_at', date('Y-m-d H:i:s'));
        $stmt->execute();

        //Show ticket in CLI
        fwrite(STDOUT, "Event ID : " . $event_id . "\n" . "Ticket Code : " . "$code" . "\n\n");
      } catch (PDOException $e) {
        //Throw error if connection to DB has fail
        echo "Error: " . $e->getMessage();
      }
      $x++;
    }
    fwrite(STDOUT, $total . " Ticket successfully generated" . "\n\n");
  } else {
    fwrite(STDOUT, "Event not found" . "\n\n");
  }
}

function checkEventIsExist($event_id)
{
  try {
    $conn = connect();
    $stmt = $conn->prepare('SELECT * FROM event WHERE id=?');
    $stmt->bindParam(1, $event_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      return false;
    } else {
      return true;
    }
  } catch (PDOException $e) {
    //Throw error if connection to DB has fail
    echo "Error: " . $e->getMessage();
  }
}

function generateTicketCode()
{
  //Prefix for the first 3 digit code
  $prefix = 'DTK';

  //Create random 7 digit code
  $randomAlphaNumeric = strtoupper(substr(md5(microtime()), 0, 7));

  return $prefix . $randomAlphaNumeric;
}
