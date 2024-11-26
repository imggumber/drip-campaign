<?php
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

?>

