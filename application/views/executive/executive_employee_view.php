<div class="user-container">
    <div class="back-btn">
        <a href="<?= site_url('Executive/emp_masterlist_view');?>">< BACK</a>
    </div>

    <h1 class="page-title"><b>View Employee Details</b></h1>

    <div class="view-emp-container">

<div class="view-box">
   
    
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
                <table class="dev-detail-table">
                    <tr>
                        <th>Employee ID</th>
                        <td><?=$employee->emp_id; ?></td>
                    </tr>
                    <tr><th>Employee Role</th>
                        <td><?=ucfirst($employee->emp_role); ?></td>
                    </tr>
                    <tr>
                        <th>Direct Superior</th>
                        <td><?=$employee->superior; ?></td>
                    </tr>
                </table>
            </div>

            <div class="employee-table-container">
                <table class="emp-table">
                    <thead>
                        <tr>
                            <th colspan="2">List of Devices Transacted With</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><b>Device ID</b></td>
                            <td><b>Device Name</b></td>
                        </tr>

                        <?php foreach($transacted_dev as $device): ?>
                            <tr>
                                <td><?=$device->borrowedDev_id;?></td>
                                <td><?=$device->borrowedDev_name;?></td>
                            </tr>
                        <?php endforeach;?>
                        
                    </tbody>
                </table>
            </div>


    
    

        </div>
    </div>
</div>