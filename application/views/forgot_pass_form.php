<section class="homepage">
    
    <div class="login_box">
        <div class="login_container">
            <div class="back-btn">
                <a class="back-btn" href="<?= site_url('Login');?>">< BACK</a>
            </div>

            <h3 class="login_header" style="text-align: center;">Forgot Password</h3>    
    
            <?= form_open('Login/forgot_password');?>
                <?php if($this->session->has_userdata('message')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->userdata('message'); ?>
                        </div>
                <?php elseif($this->session->has_userdata('success')): ?>
                        <div class="alert alert-success">
                            <?= $this->session->userdata('success'); ?>
                        </div>
                <?php endif; ?>
                <p class="reset-head" style="font-size: 20px; margin-bottom: 10px;">Enter your email address</p>
                <label for="email" class="login_label">Email</label><br>
                <input type="text" id="email" name="email"><br>
    
                    
                <input type="submit" class="all_btn" id="reset_btn" value="Reset Password" name="reset-pw">
            <?= form_close();?>     
        </div>
</section>