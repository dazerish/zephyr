<div class="user-container">

    <h1 class="page-title"><b>View Device Masterlist</b></h1>
    <span class="user-count"><?= $total; ?> Devices</span>

    <div class="searchContainer">
        <div class="search-box">
        <?= form_open_multipart('Executive/searchDev');?>
            <div class="search">
            <input type="text" class="searchTerm" name="searchTerm" placeholder="Search for a device...">
            <button type="submit" class="searchButton" name="search">
                <i class="fa fa-search"></i>
            </button>
            </div>
        </div>

        <div class="searchFilters">
            <!-- <label for="device-model">Device Model</label> -->
            <select name="device-model" id="device-model" class="filterGroup">
            <option value="">Device Model</option>
                <option value="Server">Server</option>
                <option value="Gateway">Gateway</option>
                <option value="VR Headset">VR Headset</option>
                <option value="Router">Router</option>
                <option value="Modem">Modem</option>
                <option value="Switch">Switch</option>
                <option value="Power Cable">Power Cable</option>
                <option value="RJ45">RJ45</option>
                <option value="HDMI Cable">HDMI Cable</option>
                <option value="VGA Cable">VGA Cable</option>
                <option value="Mouse">Mouse</option>
                <option value="Keyboard">Keyboard</option>
                <option value="Monitor">Monitor</option>
                <option value="Laptop">Laptop</option>
            </select>
        

            <!-- <label for="manufacturer">Manufacturer</label> -->
            <select name="manufacturer" id="manufacturer" class="filterGroup">
                <option value="">Manufacturer</option>
                <option value="Apple">Apple</option>
                <option value="Dell">Dell</option>
                <option value="Occulus">Oculus</option>
                <option value="Lenovo">Lenovo</option>
                <option value="Asus">Asus</option>
            </select>
        

        
            <!-- <label for="status">Status</label> -->
            <select name="status" id="status" class="filterGroup">
                <option value="">Status</option>
                <option value="Available">Available</option>
                <option value="Reserved">Reserved</option>
                <option value="Deployed">Deployed</option>
                <option value="Returned">Returned</option>
                <option value="Overdue">Overdue</option>
                <option value="Broken">Broken</option>
                <option value="Repaired">Repaired</option>
                <option value="Lost">Lost</option>
                <option value="Recovered">Recovered</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Decommissioned">Decommisioned</option>
            </select>

                <a href="<?= site_url('Executive/dev_masterlist_view')?>"><u>Clear All</u></a>
        </div>

        <?= form_close();?>
    </div>
        
    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Device Image</th>
                    <th>Device Name</th>
                    <th>Device Model</th>
                    <th>Manufacturer</th>
                    <th>Status</th>
                    <th>Actions</th>             
                </tr>
            </thead>

            <!--placed a placeholder so it can easily be identified and replaced with php function-->
            <tbody>
                <?php foreach($devices as $device): ?>
                    <tr>
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
            
                        <td>
                            <a href="<?= site_url('Executive/device_view/') . $device->id; ?>"><i class="fa fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>  

            </tbody>
        </table>

    </div>
            
    <div class="pagination-div">
        <?= $this->pagination->create_links() ?>
    </div>
    
</div>




