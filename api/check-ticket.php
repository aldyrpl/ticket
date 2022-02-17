<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

//Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Invalid Request Method. HTTP method should be GET',
    ]);
    exit;
}

include "../connection.php"; //include DB connection file

$conn = connect();//Connect to DB
$event_id = null;
$ticket_code = null;

try {
     //Check if we have param we needed if no we will throw the error message
    if (empty($_GET['event_id']) || empty($_GET['ticket_code'])) {
        echo json_encode([
            'success' => 0,
            'message' => 'event_id and ticket_code param is needed',
        ]);
        return;
    } else {
        $event_id = $_GET['event_id'];
        $ticket_code = $_GET['ticket_code'];
    }

    //Prepare SQL Statement
    $sql = "SELECT  ticket.ticket_code, ticket_status.status_name FROM ticket 
			INNER JOIN ticket_status ON ticket.status_id = ticket_status.id
			WHERE ticket.event_id = :event_id AND ticket.ticket_code = :ticket_code";

    $stmt = $conn->prepare($sql);

    //Bind param to SQL Statement
    $stmt->bindValue(':event_id', htmlspecialchars(strip_tags($event_id)), PDO::PARAM_STR);
    $stmt->bindValue(':ticket_code',  htmlspecialchars(strip_tags($ticket_code)), PDO::PARAM_STR);


    $stmt->execute();

    //Check if any data to show
    if ($stmt->rowCount() > 0) {
        $data = null;
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => 1,
            'data' => $data,
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'No Result Found!',
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
