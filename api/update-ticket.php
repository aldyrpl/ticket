<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

//Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Invalid Request Method. HTTP method should be POST',
    ]);
    exit;
}

include "../connection.php"; //include DB connection file

$conn = connect(); //Connect to DB
$data = json_decode(file_get_contents("php://input"));
$event_id = null;
$ticket_code = null;
$status = null;

try {
    //Check if we have param we needed if no we will throw the error message
    if (!isset($_POST['event_id']) || !isset($_POST['ticket_code']) || !isset($_POST['status'])) {
        echo json_encode([
            'success' => 0,
            'message' => 'event_id, ticket_code and status param is needed',
        ]);
        exit;
    } else {
        $event_id = $_POST['event_id'];
        $ticket_code = $_POST['ticket_code'];
        $status = $_POST['status'];
    }

    //Prepare SQL Statement
    $update_query = "UPDATE ticket SET ticket.status_id = :status, ticket.updated_at = :updated_at
                     WHERE ticket.event_id = :event_id AND
                     ticket.ticket_code = :ticket_code";

    $update_stmt = $conn->prepare($update_query);
    //Initiate updated time
    $updated_at = date('Y-m-d H:i:s');

    //Bind param to SQL Statement
    $update_stmt->bindValue(':status', htmlspecialchars(strip_tags($status)), PDO::PARAM_STR);
    $update_stmt->bindValue(':updated_at',  $updated_at, PDO::PARAM_STR);
    $update_stmt->bindValue(':event_id', htmlspecialchars(strip_tags($event_id)), PDO::PARAM_STR);
    $update_stmt->bindValue(':ticket_code', htmlspecialchars(strip_tags($ticket_code)), PDO::PARAM_STR);

    //Check if any row affected
    if ($update_stmt->execute() && $update_stmt->rowCount() == '1') {
        
        //Preparing data to response
        $data = array("ticket_code"=>$ticket_code,
                      "status"=>$status,
                      "updated_at"=>$updated_at);

        echo json_encode([
            'success' => 1,
            'data' =>  $data
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Not updated. Something is going wrong.'
        ]);
    }
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
