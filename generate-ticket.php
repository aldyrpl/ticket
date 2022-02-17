<?php
include "./connection.php"; //Include DB connection file
include "./models/ticket_model.php"; //Include ticket model

//If no argument / param
if (1 == $argc) {
    fwrite(STDOUT, "Welcome to Ticket Generator App" . "\n\n");
    help();
    return;
}

// Check if we have 2 arguments and INT, if no we'll throwing info available command
if(filter_var($argv[1], FILTER_VALIDATE_INT) > 0 && filter_var($argv[2], FILTER_VALIDATE_INT) > 0){
    //Run add ticket function from ticket model
    addTicket($argv[1], $argv[2]);
}else{
    help();
}

function help()
{
    $text = "php generate-ticket.php";
    fwrite(STDOUT, "Available commands:" . "\n");
    fwrite(STDOUT, "$text {event_id} {total_ticket}". "\n");
}