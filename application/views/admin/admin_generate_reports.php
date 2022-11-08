
<script>
    let click = document.querySelector('.regbtn');
    let list = document.querySelector('.list');
    click.addEventListener("click", ()=> {
        list.classList.toggle('newList');
    });
</script>

<div class="user-container">

    <h1 class="page-title"><b>Generate Reports</b></h1>

    <div class="report-picker">
        <?= form_open('Admin/export_csv'); ?>
            <div class="calendar">
                <label for="start_date">Start Date</label><br>
                <input type="date" id="reservation-date" class="date-picker" name="start_date">
                <span class="text-danger"><?= form_error('start_date') ?></span>
            </div>

            <div class="calendar">
                <label for="end_date">End Date</label><br>
                <input type="date" id="reservation-date" class="date-picker" name="end_date">
                <span class="text-danger"><?= form_error('end_date') ?></span>
            </div>

            

        </div>

        <div class="generate-btn-div">
            <button class="generate-btn" name="generate-report">GENERATE REPORT</button>
        </div>

        <?= form_close();?>
</div>
