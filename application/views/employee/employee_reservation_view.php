
<section class="user-container">
    <h1 class="page-title"><b>Borrow This Device</b></h1>
    <div class="borrow_module">
        <?php foreach ($stocks as $stock) : ?>
            <div class="device-view">
                <img
                    <?php if(isset($stock->dev_image)): ?> 
                        src="<?= base_url('./assets/device_image/') . $stock->dev_image; ?>" 
                        height="250px" 
                        alt="device-pic"
                    <?php endif ?>
                > 
                <h3><?= $stock->dev_name; ?></h3>
                <h3><?= $stock->unique_num; ?></h3>
                <h6>Specifications</h6>
                <div class="device-info">

                    <ul>
                        <li><?= $stock->specs; ?></li>
                        <li>RAM: 16GB 3200Mhz</li>
                        <li>Graphics: Integrated Intel HD Graphics 4000</li>
                        <li>Hard Drive: 1TB</li>
                        <li>Display: 14" inch; 1920 resolution</li>
                        <li>OS: Windows 10</li>
                    </ul>

                </div>
            </div>
        <?php endforeach; ?>
        <div class="picker-flex">
            <div class="picker-div">
                <?= form_open_multipart('Employee/set_reserveDate') ?>
                <?php if ($this->session->has_userdata('success')) : ?>
                    <div class="alert alert-success">
                        <?= $this->session->userdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($stocks as $stock) : ?>
                    <input type="hidden" name="dev-name" value="<?= $stock->dev_name; ?>">
                    <input type="hidden" name="unique-num" value="<?= $stock->unique_num; ?>">
                <?php endforeach; ?>
                <input type="hidden" name="borrower" value="<?= $employee->emp_name; ?>">

                <label for="reservation-date">Pick a reservation date:</label><br>
                <input type="datetime-local" id="reservation_date" class="date-picker" name="reservation_date">
                <span class="text-danger"><?= form_error('reservation_date'); ?></span>  

                <div class="btn-grp">
                    <button type="submit" class="cancel-btn" name="cancel-button">Cancel</button>
                    <button type="submit" class="reserve-btn" name="borrow-device">Borrow Device</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

    </div>

</section>


