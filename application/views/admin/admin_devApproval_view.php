
<script>
    let click = document.querySelector('.regbtn');
    let list = document.querySelector('.list');
    click.addEventListener("click", ()=> {
        list.classList.toggle('newList');
    });
</script>

<div class="user-container">

    <h1 class="page-title"><b>Device Approval List</b></h1>
    <span class="device-count"><?= $total; ?> Pending Devices</span>

    <?php if ($this->session->has_userdata('approved')) : ?>
            <div class="alert alert-success">
                <?= $this->session->userdata('approved'); ?>
            </div>
    <?php elseif ($this->session->has_userdata('rejected')): ?>
            <div class="alert alert-danger">
                <?= $this->session->userdata('rejected'); ?>
            </div>
    <?php endif; ?>


    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col"></th>
                    <th scope="col">Device ID</th>
                    <th scope="col">Device Name</th>
                    <th scope="col">Borrower</th>
                    <th scope="col">Reserved Date</th>
                    <th scope="col">Return Date</th>
                    <th scope="col">Actions</th>             
                </tr>
            </thead>

            <!--placed a placeholder so it can easily be identified and replaced with php function-->
            <tbody>
                <?php foreach ($transactions as $transaction) : ?>
                    <tr class="align-middle">
                        <td data-label="Transaction">
                            <img src="<?= base_url('./assets/device_image/dev-placeholder.png'); ?>" alt="device pic" class="device-pic">
                        </td>
                        <td class="emp-name-bold" data-label="Device ID"><?= $transaction->borrowedDev_id; ?></td>
                        <td data-label="Device Name"><?= $transaction->borrowedDev_name; ?></td>
                        <td data-label="Borrower"><?= $transaction->borrower; ?></td>
                        <td data-label="Reserved Date"><?= $transaction->decision_time; ?></td>
                        <td data-label="Return Date"><?= $transaction->return_date; ?></td>
                        <td data-label="Actions">
                            <div class="action-icons">
                                <a href="<?= site_url('Admin/reject_device/') .$transaction->transaction_id. '/'. $transaction->borrowedDev_id; ?>"><i class="far fa-times-circle" id="cross-icon"></i></a>
                                <a href="<?= site_url('Admin/approve_device/'). $transaction->transaction_id. '/'. $transaction->borrowedDev_id; ?>"><i class="far fa-check-circle" id="check-icon"></i></a>
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


<!-- Modal -->
<!-- Fix borrowedDev_id showing -->

<!-- <div class="modal fade" id="approveBtnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to approve?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You are going to approve. Continue?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="//enter site url" class="btn btn-success">Approve</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="declineBtnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to decline?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You are going to decline <span id="device-name"></span>. Continue?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="//enter site url" class="btn btn-danger">Decline</a>
      </div>
    </div>
  </div>
</div> -->