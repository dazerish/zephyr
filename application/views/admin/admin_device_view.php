<div class="user-container">

    <div class="back-btn">
        <a href="<?= site_url('Admin/dev_masterlist_view');?>">< BACK</a>
    </div>

    <h1 class="page-title"><b>View Device Details</b></h1>
    <div class="view-emp-container">
        <div class="view-box">
            
            <div class="detail-header">
                <img 
                    <?php if(isset($device->dev_image)): ?>
                        class="device-pic"
                        src="<?= base_url('./assets/device_image/') . $device->dev_image; ?>"
                        alt="device pic"
                        height="200px"
                    <?php endif?>
                >
                <h4><?= $device->dev_name; ?></h4>
                
            </div>

            

            <div class="detail-table-div">
                <table class="dev-detail-table">
                    <tr>
                        <th>Device Unique ID</th>
                        <td><?= $device->unique_num; ?></td>
                    </tr>
                    <tr><th>Device Model</th>
                        <td><?= $device->dev_model; ?></td>
                    </tr>
                    <tr>
                        <th>Manufacturer</th>
                        <td><?= $device->manufacturer; ?></td>
                    </tr>
                    <tr>
                        <th>Specifications</th>
                        <td><?= $device->specs; ?></td>
                    </tr>
                    <tr>
                        <th>Allowed Roles</th>
                        <td><?= $device->allowed_roles; ?></td>
                    </tr>
                    <tr>
                        <th>Current Status</th>
                        <td><?= $device->cur_status; ?></td>
                    </tr>
                    <tr>
                        <th>Previous Device Status</th>
                        <td><?= $device->prev_status; ?></td>
                    </tr>
                </table>
            </div>

            <?php if ($this->session->has_userdata('updated')) : ?>
                <div class="alert alert-success">
                    <?= $this->session->userdata('updated'); ?>
                </div>
            <?php elseif ($this->session->has_userdata('decom')): ?>
                    <div class="alert alert-danger">
                        <?= $this->session->userdata('decom'); ?>
                    </div>
            <?php endif; ?>
                    
            <div class="action-btn-div">
            <h4 class="action-grp-title">Actions</h4>
                    <?= form_open_multipart('Admin/device_status');?>
                        <input type="hidden" id="dev-id" name="dev-id" value="<?= $device->id; ?>">
                        <input type="hidden" id="unique_num" name="unique_num" value="<?= $device->unique_num; ?>">
                        <div class="action-grp">
                            <button class="action-btn" name="Deployed"><i class="fas fa-share-square"></i> Deployed</button>
                            <button class="action-btn" name="Returned"><i class="fas fa-undo-alt"></i></i> Returned</button>
                            <button class="action-btn" name="Overdue"><i class="fas fa-clock"></i> Overdue</button>
                            <button class="action-btn" name="Lost"><i class="fas fa-question"></i> Lost</button>
                            <button class="action-btn" name="Broken"><i class="fas fa-bolt"></i> Broken</button>
                        </div>
                   

                        <div class="action-grp">
                            <button class="action-btn" name="Repaired"><i class="fas fa-thumbs-up"></i> Repaired</button>
                            <button class="action-btn" name="Recovered"> <i class="fas fa-recycle"></i> Recovered</button>
                            <button class="action-btn" name="Maintenance"><i class="fas fa-tools"></i> Maintenance</button>
                            <button class="action-btn" name="Decommissioned"><i class="fas fa-ban"></i> Decommissioned</button>
                        </div>
                    <?= form_close();?>

                    <div class="action-grp">
                        <button href="#removeBtnModal" class="action-btn" data-bs-toggle="modal" data-bs-target="#removeBtnModal" ><i class="fas fa-trash-alt" id="remove-icon"></i>Remove Device</a>
                    </div>
                    
              
                
            </div>
                    
            
        
            
        </div>
       
             
        
</div>

         
<!-- Modal -->
<div class="modal fade" id="removeBtnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to remove this device?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You are going to remove <?= $device->dev_name; ?>. Continue?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= site_url('Admin/remove_device/') . $device->id; ?>" class="btn btn-danger">Remove Device</a>
      </div>
    </div>
  </div>
</div>

       
               


</section>
