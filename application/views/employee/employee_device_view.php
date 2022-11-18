<div class="user-container">
    <div class="back-btn">
        <a href="<?= site_url('Employee/dev_masterlist_view');?>">< BACK</a>
    </div>

    <h1 class="page-title"><b>View Device Details</b></h1>
    <div class="view-emp-container">
        <div class="view-box">
            <div class="detail-header">
                    <img 
                        <?php if(isset($device->dev_image)): ?>
                            class="device-pic"
                            src="<?= base_url('./assets/device_image/') . $device->dev_image; ?>"
                            alt="device pic"
                        <?php endif?>
                    >
                    <h4><?= $device->dev_name; ?></h4>
                    
                </div>

                <div class="detail-table-div">
                <table class="dev-detail-table">
                    <tr>
                        <th>Device Unique ID</th>
                        <td><?= $device->unique_num; ?></td>
                    </tr>
                    <tr><th>Device Model</th>
                        <td><?= $device->dev_model; ?></td>
                    </tr>
                    <tr>
                        <th>Manufacturer</th>
                        <td><?= $device->manufacturer; ?></td>
                    </tr>
                    <tr>
                        <th>Specifications</th>
                        <td><?= $device->specs; ?></td>
                    </tr>
                    <tr>
                        <th>Allowed Roles</th>
                        <td><?= $device->allowed_roles; ?></td>
                    </tr>
                    <tr>
                        <th>Current Status</th>
                        <td><?= $device->cur_status; ?></td>
                    </tr>
                    <tr>
                        <th>Previous Device Status</th>
                        <td><?= $device->prev_status; ?></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
           
            
</div>