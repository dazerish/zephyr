<div class="user-container">

    <h1 class="page-title"><b>View Device Masterlist</b></h1>
    <span class="user-count"><?= $total; ?> Devices</span>

    <div class="searchContainer">
        <div class="search-box">
        <?= form_open_multipart('Employee/searchDev');?>
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
                <option value="#">Device Model</option>
                <option value="Laptop">Laptop</option>
                <option value="Gateway">Gateway</option>
                <option value="Smartphone">Smartphone</option>
                </select>
        

            <!-- <label for="manufacturer">Manufacturer</label> -->
            <select name="manufacturer" id="manufacturer" class="filterGroup">
                <option value="#">Manufacturer</option>
                <option value="Laptop">Apple</option>
                <option value="Gateway">Dell</option>
                <option value="Smartphone">Lenovo</option>
                </select>
        

        
            <!-- <label for="status">Status</label> -->
            <select name="status" id="status" class="filterGroup">
                <option value="#">Status</option>
                <option value="Available">Available</option>
                <option value="Reserved">Reserved</option>
                <option value="Removed">Removed</option>
                </select>

                <a href="<?= site_url('Employee/dev_masterlist_view')?>" class="clear-filter"><u>Clear All</u></a>
        </div>

        <?= form_close();?>
    </div>
        
    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col">Device Image</th>
                    <th scope="col">Device Name</th>
                    <th scope="col">Device Model</th>
                    <th scope="col">Manufacturer</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>             
                </tr>
            </thead>

            <!--placed a placeholder so it can easily be identified and replaced with php function-->
            <tbody>
                <?php foreach($devices as $device): ?>
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
  
    <div class="pagination-div">
        <?= $this->pagination->create_links() ?>
    </div>
</div>
