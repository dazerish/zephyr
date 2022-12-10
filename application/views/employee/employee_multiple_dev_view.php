<div class="user-container">

    <div class="table-container">
            <table class="table-responsive">
                <thead>
                    <tr class="user-details">
                        <th scope="col"></th>
                        <th scope="col">Device Name</th>
                        <th scope="col">Device Model</th>
                        <th scope="col">Manufacturer</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Status</th>             
                    </tr>
                </thead>

                <!--placed a placeholder so it can easily be identified and replaced with php function-->
                <tbody>
                    <?php foreach($devices as $device): ?>
                        <tr>
                            <td data-label=""></td>
                        </tr>
                        <tr class="align-middle">
                            <td data-label="Device Image">
                                <img
                                    <?php if(isset($device->dev_image)): ?>
                                        class="device-pic"
                                        src="<?= base_url('./assets/device_image/') . $device->dev_image; ?>"
                                        alt="device-pic"
                                    <?php endif?>
                                >
                            </td>
                            <td class="emp-name-bold" data-label="Device Name"><?= $device->dev_name;?></td>
                            <td data-label="Device Model"><?= $device->dev_model;?></td>
                            <td data-label="Manufacturer"><?=$device->manufacturer; ?></td>
                            <td data-label="Status"><?=$device->cur_status; ?></td>
                
                            <td data-label="Actions">
                                <a href="<?= site_url('Employee/device_view/') . $device->id; ?>"><i class="fa fa-solid fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>  

                </tbody>
            </table>
        </div>
</div>