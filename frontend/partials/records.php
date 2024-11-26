<?php
$records = fetch_stored_details('TbCampaigns');
$data    = isset($records['data']) ? $records['data'] : '';

if (isset($records['status']) && $records['status'] == true) {    ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th class="text-center" scope="col">Action</th>
            </tr>
        </thead>
        <tbody class="table-striped">
            <?php
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $key => $d) { 
                        $id       = isset($d['id']) ? $d['id'] : '';
                        $camp_id  = isset($d['camp_id']) ? $d['camp_id'] : '';
                        $datetime = isset($d['camp_datetime']) ? $d['camp_datetime'] : '';
                        
                        if (!empty($id) && !empty($camp_id) && !empty($datetime)) { ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td>Campaign Example</td>
                                <td>Dec 25, 2024</td>
                                <td class="text-center">
                                    <a class="me-md-2 me-0 text-warning" href="?edit=<?=$id;?>">Edit</a>
                                    <a  href="?del=<?=$id;?>" class="text-danger">Delete</a>
                                </td>
                            </tr> <?php
                        } else { ?>
                            <tr class="text-center">
                               <td colspan="4">No campaigns set right now.</td> 
                            </tr> <?php
                            break;
                        }
                    } 
                }
            ?>
        </tbody>
    </table> <?php
}
