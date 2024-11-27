<?php
// Define the path to the logs folder and error log file
$logFolder = 'logs';
$errorLogFile = $logFolder . '/error.log';

// Data Source Name (DSN) and options for PDO
$dsn = "mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . ";charset=" . CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Set default fetch mode
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Disable emulated prepares
];

try {
    // Create a PDO instance (database connection)
    $pdo = new PDO($dsn, USERNAME, PASSWORD, $options);
} catch (\PDOException $e) {
    // Handle connection errors (e.g., show a message or log the error)
    add_log("Database connection failed: " . $e->getMessage()); 
    exit();
}



