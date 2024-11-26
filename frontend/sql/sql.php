<?php
$table_name   = 'TbCampaigns';
$campagin_err = $datetime_err = false;

// Insert Campaign data
if (isset($_POST['add-campaign-btn'])) {
    $campaign = isset($_POST['select-campaign']) ? $_POST['select-campaign'] : '';
    $datetime = isset($_POST['select-datetime']) ? $_POST['select-datetime'] : '';

    // Converting time format for MySQL (YYYY-MM-DD HH:MM:SS)
    $formattedDateTime = str_replace('T', ' ', $datetime) . ':00';
    echo $formattedDateTime;

    if (empty($campaign)) {
        $campagin_err = true;
        exit;
    }

    if (empty($datetime)) {
        $datetime_err = true;
        exit;
    }

    try {
        $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the table exists
        $table = $conn->query("SHOW TABLES LIKE '" . $table_name . "'");

        if ($table) {
            try {
                // Prepare the SQL statement with proper spacing and placeholders
                $stmt = $conn->prepare("INSERT INTO " . $table_name . " (camp_id, camp_datetime) VALUES (:camp_id, :camp_datetime)");

                // Bind the parameters
                $stmt->bindParam(':camp_id', $campaign);
                $stmt->bindParam(':camp_datetime', $formattedDateTime);

                // Execute the query
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit; // make sure the script stops executing after the redirect
            } catch (PDOException $e) {
                add_log($e->getMessage());
            }
        } else {
            add_log("Table " . $table_name . " doesn't exist.");
        }
    } catch (PDOException $e) {
        add_log($e->getMessage());
    }
}

// Fetch stored campaign details
function fetch_stored_details($table_name)
{
    $result = '';
    $status = false;
    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if the table exists
    $table = $conn->query("SHOW TABLES LIKE '" . $table_name . "'")->fetch();
    
    if ($table) {
        try {
            // Prepare and execute the query to fetch campaign details
            $stmt = $conn->prepare("SELECT id, camp_id, camp_datetime FROM " . $table_name);
            $stmt->execute();

            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $status = true;
            
        } catch (PDOException $e) {
            add_log("Error: " . $e->getMessage());
        }
    } else {
        add_log("Internal Server Error. Base table name ".$table_name." doesn't exist");
    }

    $response = [
        'status'=> $status,
        'data'  => $result
    ];

    return $response;
}


