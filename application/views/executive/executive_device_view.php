<div class="view-emp-container">
    <div class="back-btn">
        <a href="<?= site_url('Executive/dev_masterlist_view');?>">< BACK</a>
    </div>

    <h1 class="page-title"><b>View Device Details</b></h1>

        <div class="detail-container">
            
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
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th scope="col">Device Unique ID</th>
                            <th scope="col">Device Model</th>
                            <th scope="col">Manufacturer</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr class="align-middle">
                            <td><?= $device->unique_num; ?></td>
                            <td><?= $device->dev_model; ?></td>
                            <td><?= $device->manufacturer; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="spec-role-flex">
                <div class="specifications">
                    <table class="transacted">
                        <thead>
                            <tr>
                                <th>Specifications</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            <tr>
                                <td><?= $device->specs; ?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                <div class="all-roles-div">
                    <table class="transacted">
                        <thead>
                            <tr>
                                <th>Allowed Roles</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            <tr>
                                <td><?= $device->allowed_roles; ?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="statuses-table">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th scope="col">Current Status</th>
                            <th scope="col">Previous Device Status</th> 
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr>
                            <td><?= $device->cur_status; ?></td> 
                            <td><?= $device->prev_status; ?></td> 
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
</div>