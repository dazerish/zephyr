<section class="logo-section">
    <div class="login_logo">
        <img src="<?= base_url('./assets/pictures/calibr8logo.png');?>" alt="Calibr8 Logo" height="30px">
    </div>
</section>

    
    <section class="homepage">
        
            <div class="login_box">
                <div class="login_container">

                    <h3 class="login_header">Sign in your Calibr8 Account</h3>    

                    <?= form_open('Login/login_validate'); ?>
                        <?php if($this->session->has_userdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->userdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <label for="email" class="login_label">Email</label><br>
                        <input class="login-form" type="text" id="email" name="email"><br>
                        <label for="password" class="login_label">Password</label><br>
                        <input class="login-form" type="password" id="pword" name="password"><br>
                
                        <input type="submit" class="all_btn" id="login_btn" value="LOGIN" name="login">
                   
                        <?= form_close(); ?>
                        <div class="account_notice">
                            <p>Forgot your password? <a href="<?= site_url('Login/forgot_password_view');?>"><u>Reset Password</u></a></p>
                            <p>Don't have an account yet? <b>Contact the Admin Office.</b></p>
                        </div>
                </div>        
            </div>
    </section>