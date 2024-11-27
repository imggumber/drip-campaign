<?php
// Create log files
createLogDir();

// Date format
function display_date($date){
    $date = date_create($date);
    return date_format($date,"M d, Y h:i:A");
}

function precheck($value) {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}

?>

