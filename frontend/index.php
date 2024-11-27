<?php
require './api/api.php';
$edit_record     = '';
$campaigns       = get_campaigns(); // Fetch all campaigns
$campaigns_lists = isset($campaigns['data']['results']) ? $campaigns['data']['results'] : '';


// Fetch single record
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_record = single_record($_GET['edit']);
}

$et_mysql_id = $et_camp_id = $camp_datetime = '';
if (is_array($edit_record) && !empty($edit_record)) {
    $et_mysql_id   = isset($edit_record['id']) ? $edit_record['id'] : '';
    $et_camp_id    = isset($edit_record['camp_id']) ? $edit_record['camp_id'] : '';
    $camp_datetime = isset($edit_record['camp_datetime']) ? $edit_record['camp_datetime'] : '';
}

?>


<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drip Campaign</title>
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-column" style="padding: 20vh 0px;">
            <form method="post" class="w-50 mb-3">
                <div class="mb-3">
                    <select id="select-campaign" name="select-campaign" class="form-select form-select mb-3" aria-label="Large select example" required>
                        <option value="" selected>Select campaign</option>

                        <?php
                            if (is_array($campaigns_lists) && !empty($campaigns_lists)) {
                                foreach (array_reverse($campaigns_lists) as $key => $campaign) {
                                    $id   = isset($campaign['id']) ? $campaign['id'] : '';
                                    $name = isset($campaign['name']) ? $campaign['name'] : '';
                                    if (!empty($id) && !empty($name)) { ?>
                                        <option value="<?= $id; ?>" <?= $id == $et_camp_id ? 'selected' : ''; ?>><?= $name; ?></option> <?php
                                    }
                                }
                            }
                        ?>
                    </select>
                    <?= $campagin_err ? '<p class="text-danger">This field is required</p>' : ''; ?>
                </div>
                <div class="mb-3">
                    <input type="datetime-local" name="select-datetime" value="<?= !empty($camp_datetime) ? $camp_datetime : ''; ?>" class="form-control mb-3" id="datetime-local">
                    <div id="passwordHelpBlock" class="mt-1 form-text">
                        <span>Please provide date and time according to the New York time zone (Eastern Standard Time, EST / Eastern Daylight Time, EDT).</span>
                    </div>
                </div>
                <div class="mb-3 text-center">
                    <input type="hidden" name="edit-data" value="<?= !empty($et_mysql_id) ? $et_mysql_id : ''; ?>">
                    <button id="add-campaign-btn" name="add-campaign-btn" type="submit" class="btn btn-primary">Add Campaign</button>
                </div>
            </form>

            <div class="w-50">
                <?php  require './partials/records.php' ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>