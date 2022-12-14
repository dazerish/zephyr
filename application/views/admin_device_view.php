<div class="view-emp-container">
    <h1 class="page-title"><b>View Device Details</b></h1>

    <div class="detail-container">
        <div class="remove-btn-div">
            <a class="remove-btn" data-toggle="modal" data-target="#exampleModalCenter" href="<?= site_url('Admin/remove_device/') . $device->id; ?>"><i class="fas fa-trash-alt" id="remove-icon"></i>Remove Device</a>
        </div>

        <div class="detail-header">
            <img <?php if (isset($device->dev_image)) : ?> class="device-pic" src="<?= base_url('./assets/device_image/') . $device->dev_image; ?>" alt="device pic" <?php endif ?>>
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

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure you want to remove this device?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Removing this device is yes
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Remove Device</button>
            </div>
        </div>
    </div>
</div>