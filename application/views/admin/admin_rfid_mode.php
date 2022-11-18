<div class="user-container">

    <h1 class="page-title"><b>Arduino</b></h1>


    <div class="table-container">
        <?php if ($this->session->has_userdata('registration')) : ?>
                <div class="alert alert-success">
                    <?= $this->session->userdata('registration'); ?>
                </div>
        <?php elseif ($this->session->has_userdata('attendance')): ?>
                <div class="alert alert-success">
                    <?= $this->session->userdata('attendance'); ?>
                </div>
        <?php endif; ?>
        <table class="table-responsive" id="">
            <thead>
                <tr class="device-details">
                    <th>Arduino Name</th>
                    <th>Arduino Type</th>
                    <th>Arduino UID</th>
                    <th>Arduino Mode</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($arduino as $data): ?>
                    <tr>
                        <td data-label="Arduino Name"><?=$data->name;?></td>
                        <td data-label="Arduino Type"><?=$data->type;?></td>
                        <td data-label="Arduino UID"><?=$data->device_uid;?></td>

                        <td data-label="Arduino Mode">
                            <div class="action-icon">
                                <a href="<?= site_url('Admin/arduino_registration/') .$data->id; ?>" class="ard-btn">Registration</a>
                                <a href="<?= site_url('Admin/arduino_attendance/') .$data->id; ?>" class="ard-btn">Attendance</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

