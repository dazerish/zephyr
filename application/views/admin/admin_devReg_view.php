<!-- <script>
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script> -->

<section class="main_container">

  <div class="register_container">


    <div class="login_box">
      <p class="login_header">Device Registration</p>
      
      <!-- FORM HERE -->
      <?= form_open_multipart('Admin/device_registration'); ?>

      <?php if ($this->session->has_userdata('success')) : ?>
              <div class="alert alert-success">
                  <?= $this->session->userdata('success'); ?>
              </div>
      <?php endif; ?>

      <div class="form-step">
<!--         
        <label for="tap-rfid" class="register_label">Tap your RFID</label><br> -->
        <label for="rfidNum" class="register_label">RFID Number</label>

        <i class="far fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Tap your RFID"></i>

        <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        });
        </script>

        <div id="devReg_rfid">
          
        </div>
        <span class="text-danger"><?= form_error('rfidNum') ?></span>


        <!-- <img src="<?= base_url('./assets/pictures/rfid.png'); ?>" height="150px"  alt="rfid" class="rfid-img"> -->

        

      </div>

      <div class="form-step">
        <div class="row">
          <div class="col">
            <label for="uniquenum" class="register_label">Device Serial Number</label><br>
            <input type="text" id="uniquenum" name="uniquenum"><br>
            <span class="text-danger"><?= form_error('uniquenum') ?></span>

            <label for="devicename" class="register_label">Device Name</label><br>
            <input type="text" id="devicename" name="devicename"><br>
            <span class="text-danger"><?= form_error('devicename') ?></span>

            <label for="model" class="register_label">Device Model</label><br> <!-- Device Model -->
            <select name="model" id="roles">
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
            </select><br>
            <span class="text-danger"><?= form_error('model') ?></span>

            <label for="roles" class="register_label">Allowed Roles</label><br>
              <input type="checkbox" id="role1" name="roles[]" value="Executive">
              <label for="role1"> Executive</label><br>
              <input type="checkbox" id="role2" name="roles[]" value="Employee">
              <label for="role2"> Employee</label>
              <br><br>
            <span class="text-danger"><?= form_error('roles[]') ?></span>

          </div>

          <div class="col">
            <label for="manuf" class="register_label">Manufacturer</label><br>
            <input type="text" id="manuf" name="manuf"><br>
            <span class="text-danger"><?= form_error('manuf') ?></span>

            <label for="specs" class="register_label">Specifications</label><br>
            <textarea rows="1" cols="50" wrap="physical" id="specs" name="specs"></textarea><br>
            <span class="text-danger"><?= form_error('specs') ?></span>

            <label for="category" class="register_label">Category</label><br>
            <select name="category" id="roles">
              <option value="Specialized">Specialized</option>
              <option value="Networking">Networking</option>
              <option value="Peripherals">Peripherals</option>
              <option value="Output">Output</option>
              <option value="Processing">Processing</option>
            </select><br>
            <span class="text-danger"><?= form_error('category') ?></span>

            <label for="emp-img" class="register_label">Device Image</label><br>
            <input type="file" id="upload" name="device_image" hidden />
            <label for="upload" class="upload-btn">Upload image </label>
            <span class="text-danger" id="file-chosen"><?= form_error('device_image') ?></span>
          </div>


          <div class="btns-group">
          <input type="submit" class="btn-reg" id="btn-reg" name="reg-dev" value="REGISTER DEVICE">
        </div>
          

        </div>

      </div>

      
      <?= form_close(); ?>
    </div>

  </div>


</section>

<!-- 
<script>
  const prevBtns = document.querySelectorAll(".btn-prev");
  const nextBtns = document.querySelectorAll(".btn-next");
  const progress = document.getElementById("progress");
  const formSteps = document.querySelectorAll(".form-step");
  const progressSteps = document.querySelectorAll(".progress-step");

  let formStepsNum = 0;

  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      formStepsNum++;
      updateFormSteps();
      updateProgressbar();
    });
  });

  prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      formStepsNum--;
      updateFormSteps();
      updateProgressbar();
    });
  });

  function updateFormSteps() {
    formSteps.forEach((formStep) => {
      formStep.classList.contains("form-step-active") &&
        formStep.classList.remove("form-step-active");
    });

    formSteps[formStepsNum].classList.add("form-step-active");
  }

  function updateProgressbar() {
    progressSteps.forEach((progressStep, idx) => {
      if (idx < formStepsNum + 1) {
        progressStep.classList.add("progress-step-active");
      } else {
        progressStep.classList.remove("progress-step-active");
      }
    });

    const progressActive = document.querySelectorAll(".progress-step-active");

    progress.style.width =
      ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
  }

</script> -->
<script>
    //Ajax Function
    $(document).ready(function(){
        refreshRfid();
    });

    function refreshRfid(){
        $("#devReg_rfid").load("<?php echo base_url('Admin/devReg_rfid')?>", function(){
            setTimeout(refreshRfid, 2000);
        });
    }
</script>