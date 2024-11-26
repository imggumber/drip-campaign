<?php
// Establish Connection
function establish_connection()
{
    try {
        $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE . "", USERNAME, PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result      = $conn->query("SHOW TABLES LIKE 'TbCampaigns'");
        $tableExists = $result !== false && $result->rowCount() > 0;

        if (! $tableExists) {
            $sql = "CREATE TABLE `db_listmonk`.`TbCampaigns` (`id` BIGINT NOT NULL AUTO_INCREMENT , `camp_id` BIGINT NOT NULL , `camp_datetime` DATETIME NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated-at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`))";
            
            try {
                $conn->exec($sql);
            } catch (PDOException $e) {
                add_log($e->getMessage());
            }
        }

    } catch (PDOException $e) {
        add_log($e->getMessage());
        echo "<h1>Failed to establish connection.</h1>";
        die;
    }
}

// Create log folder and files
function createLogDir()
{
    $logFolder       = 'logs';
    $errorLogFile    = $logFolder . '/error.log';
    $listmonkLogFile = $logFolder . '/listmonk.log';

    // Check if the "logs" folder exists
    if (!is_dir($logFolder)) {
        // If the folder doesn't exist, create it
        if (!mkdir($logFolder, 0777, true)) {
            // Handle error if the folder cannot be created
            echo "Failed to create the logs directory.";
            exit;
        }
    }

    // Check if the "error.log" file exists
    if (!file_exists($errorLogFile)) {
        // If the file doesn't exist, create it
        if (!touch($errorLogFile)) {
            // Handle error if the file cannot be created
            echo "Failed to create the logs file.";
            exit;
        }
    }

    if (!file_exists($listmonkLogFile)) {
        // If the file doesn't exist, create it
        if (!touch($listmonkLogFile)) {
            // Handle error if the file cannot be created
            echo "Failed to create the activity file.";
            exit;
        }
    }


    // Optional: To ensure that the logs file is writable (if necessary)
    if (!is_writable($errorLogFile)) {
        echo "The log file is not writable.";
        exit;
    }

    if (!is_writable($listmonkLogFile)) {
        echo "The activity file is not writable.";
        exit;
    }
}

// Add logs
function add_log($message, $logFile = 'logs/error.log')
{
    // If the file doesn't exist, create it
    if (!file_exists($logFile)) {
        // Check if the logs folder exists, create it if not
        $logFolder = dirname($logFile);

        if (!is_dir($logFolder)) {
            mkdir($logFolder, 0777, true);
        }
        
        // Create the error.log file
        touch($logFile);
    }

    // If the file is still not writable, return an error message
    if (!is_writable($logFile)) {
        echo "The log file is not writable!";
        return;
    }

    // Append the log message with a timestamp
    $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;

    // Append the log message to the log file
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Send API Request
function sendApiRequest($method, $apiUrl, $token = API_TOKEN, $data = null) {
    // Initialize cURL session
    $ch = curl_init();
    
    // Set default options for the cURL request
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: token ' . $token,
        'Content-Type: application/json'
    ]);
    
    // Set the HTTP request method (GET, POST, PUT, DELETE, etc.)
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    
    // If there's data (for POST or PUT), attach it to the request
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    // Execute the request and get the response
    $response = curl_exec($ch);
    
    // Check for errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "cURL Error: " . $error;
    }
    
    // Close the cURL session
    curl_close($ch);
    
    // Return the response
    return json_decode($response, true);  // Decode the JSON response into an associative array
}


