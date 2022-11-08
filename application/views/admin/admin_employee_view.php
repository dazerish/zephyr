<div class="view-emp-container">

    <div class="back-btn">
        <a href="<?= site_url('Admin/emp_masterlist_view');?>">< BACK</a>
    </div>

    <h1 class="page-title"><b>View Employee Details</b></h1>

        <div class="detail-container">
            <div class="remove-btn-div">
            <a href="#removeBtnModal" class="remove-btn" data-bs-toggle="modal" data-bs-target="#removeBtnModal" ><i class="fas fa-trash-alt" id="remove-icon"></i>Remove Employee</a>
            </div>
            
            <div class="detail-header">
                
                <img 
                    <?php if(isset($employee->emp_image)): ?>
                        class="emp-pic"
                        src="<?= base_url('./assets/users_image/') . $employee->emp_image; ?>"
                        alt="employee pic"
                    <?php endif?>
                >
                <h4><?=$employee->emp_name; ?></h4>
                
            </div>

            

            <div class="detail-table-div">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Employee Role</th>
                            <th scope="col">Direct Superior</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr class="align-middle">
                            <td><?=$employee->emp_id; ?></td>
                            <td><?=ucfirst($employee->emp_role); ?></td>
                            <td><?=$employee->superior; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="transacted-div">
                <table class="transacted">
                    <thead>
                        <tr>
                            <th>List of Devices Transacted With</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Macbook Pro</td> <!-- Transaction to follow -->
                        </tr>
                        <tr>
                            <td>Meta Quest</td> <!-- Transaction to follow -->
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            
        </div>
</div>


<!-- Modal -->
<div class="modal fade" id="removeBtnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to remove this employee?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You are going to remove <?=$employee->emp_name; ?>. Continue?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= site_url('Admin/remove_employee/') . $employee->id; ?>" class="btn btn-danger">Remove Employee</a>
      </div>
    </div>
  </div>
</div>