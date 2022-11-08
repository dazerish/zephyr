<div class="user-container">
    <div class="profile-container">
        <div class="login_box">
        <h1 class="page-title"><b>My Profile</b></h1>
            <div class="profile-deets">
                <img src="<?= base_url('./assets/users_image/' . $admin->emp_image); ?>" alt="profile picture" class="prof-pic">  
                <h4 class="profile-name"><?= $admin->emp_name; ?></h4>
                <div class="profile-details">
                    <p class="profile-p"><b>Employee ID:</b> <?= $admin->emp_id; ?></p>
                    <p class="profile-p"><b>Employee:</b> <?= ucfirst($admin->emp_role); ?></p>
                    <p class="profile-p"><b>Direct Superior:</b> <?= $admin->superior; ?></p>
                </div>
            </div>

            <div class="logout-btn-div">
                <a href="<?= site_url('Login/logout')?>">
                    <button id="logout-btn">Logout</button>
                </a>
            </div>
        </div>

        <div class="login_box">
            <div class="profile-deets">
                <?= form_open('Admin/reset_password');?>
                <h4 class="resetpw-h4" style="text-align: center; font-weight: 700;">Reset Password</h4>
                    <?php if($this->session->has_userdata('success')): ?>
                            <div class="alert alert-success">
                                <?= $this->session->userdata('success'); ?>
                            </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="oldPass">Current Password</label>
                        <input type="password" class="form-control" id="oldPass" name="oldPass" placeholder="Enter your current password">
                        <span class="text-danger"><?= form_error('oldPass'); ?></span>

                        <label for="newPass">New Password</label>
                        <input type="password" class="form-control" id="newPass" name="newPass" placeholder="Enter your new password">
                        <span class="text-danger"><?= form_error('newPass'); ?></span>

                        <label for="confNewPass">Confirm New Password</label>
                        <input type="password" class="form-control" id="confNewPass" name="confNewPass" placeholder="Confirm your new password">
                        <span class="text-danger"><?= form_error('confNewPass'); ?></span>
                    </div>

                    <div class="btn-div">
                        <input type="submit" id="reset-btn" name="reset-btn" value="Reset Password">
                    </div> 
                <?= form_close(); ?>      
            </div>
        </div>
    </div>
</div>