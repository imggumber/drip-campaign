<?php
// Define the path to the logs folder and error log file
$logFolder = 'logs';
$errorLogFile = $logFolder . '/error.log';

try {
  $conn = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE."", USERNAME, PASSWORD);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    add_log($e->getMessage());
    echo "<h1>Failed to establish connection.</h1>";
    die;
}
?>