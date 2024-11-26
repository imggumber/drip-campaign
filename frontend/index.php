<?php
require './api/api.php';

$campaigns        = get_campaigns(); // Fetch all campaigns
$single_campaigns = get_campaigns(1); // Fetch single campaign

$campaigns_lists = isset($campaigns['data']['results']) ? $campaigns['data']['results'] : '';
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
                    <select id="select-campaign" class="form-select form-select mb-3" aria-label="Large select example" required>
                        <option value="" selected>Select campaign</option>

                        <?php
                            if (is_array($campaigns_lists) && !empty($campaigns_lists)) {
                                foreach (array_reverse($campaigns_lists) as $key => $campaign) {
                                    $id   = isset($campaign['id']) ? $campaign['id'] : '';
                                    $name = isset($campaign['name']) ? $campaign['name'] : '';
                                    if (!empty($id) && !empty($name)) { ?>
                                        <option value="<?= $id; ?>"><?= $name; ?></option> <?php
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="datetime-local" name="select-datetime" class="form-control mb-3" id="datetime-local">
                </div>
                <div class="mb-3 text-center">
                    <button id="add-campaign-btn" type="submit" class="btn btn-primary">Add Campaign</button>
                </div>
            </form>

            <div class="w-50">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date</th>
                            <th class="text-center" colspan="2" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-striped">
                        <tr>
                            <td>1</td>
                            <td>Campaign Example</td>
                            <td>Dec 25, 2024</td>
                            <td class="text-end"><button type="button" class="btn btn-sm btn-outline-warning">Edit</button></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>