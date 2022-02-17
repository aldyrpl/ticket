<?php

// Generate Event
function addEvent($event_name)
{
  try {
    //Connect to DB
    $conn = connect();

    //Preparing SQL statement
    $stmt = $conn->prepare("INSERT INTO event (event_name, created_at) 
      VALUES (:event_name, :created_at)");

    //Bind param to SQL Statement
    $stmt->bindParam(':event_name', htmlspecialchars(strip_tags($event_name)));
    $stmt->bindParam(':created_at', date('Y-m-d H:i:s'));
    $stmt->execute();

    //Show event ID in CLI
    fwrite(STDOUT, "\nEvent ID : " . $conn->lastInsertId() . "\nEvent Name : " . $event_name . "\n\nSuccessfully generated\n" . "\n");

    $conn = null;
  } catch (PDOException $e) {
    //Throw error if connection to DB has fail
    echo "Error: " . $e->getMessage();
  }
}
