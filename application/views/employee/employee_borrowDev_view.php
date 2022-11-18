
<div class="user-container">

    <h1 class="page-title"><b>Borrowable Device List</b></h1>
    <span class="device-count"><?= $total; ?> devices</span>

    <?php if ($this->session->has_userdata('success')) : ?>
            <div class="alert alert-success">
                <?= $this->session->userdata('success'); ?>
            </div>
    <?php endif; ?>

    <div class="searchContainer">
        <div class="search-box">
            <?= form_open_multipart('Employee/search_BorrowableDev');?>
            <div class="search">
                <input type="text" id="searchTerm" class="searchTerm" name="searchTerm" placeholder="Search for a device...">
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

            <!--            
        <label for="manufacturer">Manufacturer</label> -->
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

            <a href="<?= site_url('Employee'); ?>"><u>Clear All</u></a>
        </div>

        <?= form_close(); ?>

    </div>

    <div class="table-container">
        <table class="table-responsive" id="">
            <thead>
                <tr class="device-details">
                    <th>Device Image</th>
                    <th>Device Name</th>
                    <th>Status</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($stocks as $stock) : ?>
                    <tr>
                        <td data-label="Device Image">
                        <img
                            <?php if(isset($stock->dev_image)): ?>
                                class="device-pic" 
                                src="<?= base_url('./assets/device_image/') . $stock->dev_image; ?>" 
                                alt="device-pic"
                            <?php endif?>
                        >
                        </td>
                        <td data-label="Device Name">
                            <?= $stock->dev_name; ?>
                        </td>

                        <td data-label="Status"> 
                            <?= $stock->cur_status; ?>
                        </td>

                        <td data-label="Stock">
                            <?= $stock->stock; ?>
                        </td>
                        <td data-label="Action">
                            <div class="item-btn">
                                <a href="<?= site_url('Employee/reserveDev/') . $stock->dev_name; ?>">
                                    <input type="submit" class="all_btn" id="reserve_btn" value="Borrow Device">
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-div">
        <?= $this->pagination->create_links(); ?>
    </div>

</div>
