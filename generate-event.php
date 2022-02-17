<?php
include "./connection.php"; //Include DB connection file
include "./models/event_model.php"; //Include ticket model

//If no argument / param
if (1 == $argc) {
    fwrite(STDOUT, "Welcome to Event Generator App" . "\n\n");
    help();
    return;
}

// Check if we have 1 arguments if no we'll throwing info available command
if(isset($argv[1])){
    //Run add ticket function from ticket model
    addEvent($argv[1]);
}else{
    help();
}

function help()
{
    $text = "php generate-event.php";
    fwrite(STDOUT, "Available commands:" . "\n");
    fwrite(STDOUT, "$text \"Event Name\"". "\n");
}