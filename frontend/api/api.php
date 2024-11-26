<?php
require './config/config.php';

/**
 * Campaigns
 */

//  Get campaigns
function get_campaigns($id = '')
{
    $response = [];
    
    if (!empty($id)) {
        if (is_numeric($id)) {
            if ($id > 0) {
                $api_url  = API_PATH . '/campaigns/' . $id;
                $response = sendApiRequest('GET', $api_url);
            } else {
                add_log("Failed to fetch single campaign");
                $response = [
                    'message' => 'Invalid request',
                    'status'  => 400
                ];
            }
        } else {
            $response = [
                'message' => 'Invalid syntax',
                'status'  => 400
            ];
        }
    } else {
        $api_url  = API_PATH . '/campaigns';
        $res = sendApiRequest('GET', $api_url);
        if (!empty($res) || $res != NULL ) {
            $response = $res;
        } else {
            add_log("Failed to fetch campaigns list");
        }
    }

    return $response;
}
