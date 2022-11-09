<section class="main_container">

  <div class="register_container">

    <div class="login_box">
      <p class="login_header">Employee Registration</p>
      <div class="progressbar">
        <div class="progress" id="progress"></div>
        <div class="progress-step progress-step-active" data-title="Employee Details"></div>
        <div class="progress-step" data-title="Employee RFID"></div>
      </div>
      <!-- FORM HERE -->
      <?= form_open_multipart('Admin/employee_registration'); ?>
      <?php if ($this->session->has_userdata('success')) : ?>
        <div class="alert alert-success">
          <?= $this->session->userdata('success'); ?>
        </div>
      <?php endif; ?>
      <div class="form-step form-step-active">
        <div class="row">
          <div class="col">
            <label for="empid" class="register_label">Employee ID</label><br>
            <input type="text" id="empid" name="empid"><br>
            <span class="text-danger"><?= form_error('empid') ?></span>

            <label for="empname" class="register_label">Employee Name</label><br>
            <input type="text" id="empname" name="empname"><br>
            <span class="text-danger"><?= form_error('empname') ?></span>

            <label for="email" class="register_label">Employee Email</label><br>
            <input type="text" id="email" name="email"><br>
            <span class="text-danger"><?= form_error('email') ?></span>

            <label for="superior" class="register_label">Direct Superior</label><br>
            <input type="text" id="superior" name="superior"><br>
            <span class="text-danger"><?= form_error('superior') ?></span>
          </div>

          <div class="col">
            <label for="roles" class="register_label">Employee Role</label><br>
            <select name="roles" id="roles">
              <option value="administrator">Administrator</option>
              <option value="employee">Employee</option>
              <option value="executive">Executive</option>
            </select><br>
            <span class="text-danger"><?= form_error('roles') ?></span>

            <label for="init-pass" class="register_label">Initial Password</label><br>
            <input type="password" id="init-pass" name="init-pass"><br>
            <span class="text-danger"><?= form_error('init-pass') ?></span>

            <label for="employee-image" class="register_label">Employee Image</label><br>
            <input type="file" id="upload" name="employee_image" hidden />
            <label for="upload" class="upload-btn">Upload image </label>
            <span class="text-danger" id="file-chosen"><?= form_error('employee_image') ?></span>
          </div>

          <div class="reg-div">
            <a href="#" class="btn btn-next" id="btn-next">NEXT</a>
          </div>

        </div>

      </div>


      <div class="form-step">
        <label for="rfid-num" class="register_label">RFID Number</label><br>
        <input type="text" id="rfid-num" name="rfid-num"><br>
        <span class="text-danger"><?= form_error('rfid-num') ?></span>

        <label for="tap-rfid" class="register_label">Tap your RFID</label><br>
        <textarea rows="1" cols="50" wrap="physical" id="tap-rfid" name="tap-rfid"></textarea><br>
        <span class="text-danger"><?= form_error('tap-rfid') ?></span>

        <div class="btns-group">
          <a href="#" class="btn btn-prev" id="btn-prev">PREVIOUS</a>
          <input type="submit" class="btn-reg" id="btn-reg" name="reg-emp" value="REGISTER EMPLOYEE">
        </div>

      </div>
      <?= form_close(); ?>

    </div>

  </div>

</section>


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
</script>