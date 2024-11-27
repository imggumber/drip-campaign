<?php
$table_name   = 'TbCampaigns';
$campagin_err = $datetime_err = false;

// Insert and Update Campaign Data
if (isset($_POST['add-campaign-btn'])) {
    $edit_data = isset($_POST['edit-data']) ? $_POST['edit-data'] : '';
    $campaign  = isset($_POST['select-campaign']) ? $_POST['select-campaign'] : '';
    $datetime  = isset($_POST['select-datetime']) ? $_POST['select-datetime'] : '';

    // Converting time format for MySQL (YYYY-MM-DD HH:MM:SS)
    $formattedDateTime = str_replace('T', ' ', $datetime) . ':00';

    if (empty($campaign)) {
        $campagin_err = true;
        exit;
    }

    if (empty($datetime)) {
        $datetime_err = true;
        exit;
    }

    try {
        // Check if the table exists
        $table = $pdo->query("SHOW TABLES LIKE '" . $table_name . "'")->fetch();

        if ($table) {
            try {
                // Check if $edit_data is empty (indicating an insert operation)
                if (empty($edit_data)) {
                    // Insert operation
                    $stmt = $pdo->prepare("INSERT INTO " . $table_name . " (camp_id, camp_datetime) VALUES (:camp_id, :camp_datetime)");

                    // Bind the parameters for insertion
                    $stmt->bindParam(':camp_id', $campaign);
                    $stmt->bindParam(':camp_datetime', $formattedDateTime);

                    // Execute the insert query
                    $stmt->execute();

                    // Redirect after the insert
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit(); // Make sure the script stops executing after the redirect
                } else {
                    // Update operation
                    $sql = "UPDATE " . $table_name . " SET camp_id=:camp_id, camp_datetime=:camp_datetime WHERE id=:id";

                    // Prepare the update statement
                    $stmt = $pdo->prepare($sql);

                    // Bind the parameters for update
                    $stmt->bindParam(":camp_id", $campaign);
                    $stmt->bindParam(":camp_datetime", $datetime);
                    $stmt->bindParam(":id", $edit_data);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Records updated successfully. Redirect to the landing page
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
            } catch (PDOException $e) {
                // Log the error message in case of failure
                add_log($e->getMessage());
            }
        } else {
            // Log error if the table doesn't exist
            add_log("Table " . $table_name . " doesn't exist.");
        }
    } catch (PDOException $e) {
        // Log connection error
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
        add_log("Internal Server Error. Base table name " . $table_name . " doesn't exist");
    }

    $response = [
        'status' => $status,
        'data'  => $result
    ];

    return $response;
}

// Fetch single record
function single_record($id, $table_name = 'TbCampaigns')
{
    $result = '';
    $status = false;
    $sql_id = $id;

    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception

    $sql    = "SELECT * FROM " . $table_name . " WHERE id = :id";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $sql_id);
    }
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Fetch result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            add_log("Something went wrong while fetching record");
            exit();
        }
    } else {
        add_log("Oops! Something went wrong. Please try again later.");
    }
}

if (isset($_GET['del']) && !empty($_GET['del'])) {
    $id = $_GET['del'];
    // Prepare a delete statement
    $sql = "DELETE FROM " . $table_name . " WHERE id = :id";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $id);
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
