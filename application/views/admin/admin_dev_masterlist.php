<div class="user-container">

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

    <h1 class="page-title"><b>View Device Masterlist</b></h1>
    <span class="user-count"><?= $total; ?> Devices</span>

    <div class="searchContainer">
        <div class="search-box">
        <?= form_open_multipart('Admin/searchDev');?>
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

                <a href="<?= site_url('Admin/dev_masterlist_view')?>" class="clear-btn"><u>Clear All</u></a>
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
            <tbody>
                <?php foreach($devices as $device): ?>
                    <tr>
                        <td data-label="Device Image">
                            <img
                                <?php if(isset($device->dev_image)): ?>
                                    class="device-pic"
                                    src="<?= base_url('./assets/device_image/') . $device->dev_image; ?>"
                                    alt="device-pic"
                                    onerror="this.onerror=null; this.src='<?= base_url('./assets/pictures/icons/dev-ph'); ?>'"
                                <?php endif ?>
                            >
                        </td>
                        <td class="emp-name-bold" data-label="Device Name"><?= $device->dev_name;?></td>
                        <td data-label="Device Model"><?= $device->dev_model;?></td>
                        <td data-label="Manufacturer"><?=$device->manufacturer; ?></td>
                        <td data-label="Status"><?=$device->cur_status; ?></td>
            
                        <td data-label="Actions" class="actions-div">
                            <a href="<?= site_url('Admin/device_view/') . $device->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View device details"><i class="fa fa-solid fa-eye"></i></a>
                            <a href="<?= site_url('Admin/editDev_view/') . $device->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit device details"><i class="fas fa-edit" id="edit-btn"></i></a>
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


