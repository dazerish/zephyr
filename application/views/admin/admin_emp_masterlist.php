<div class="user-container">

    <h1 class="page-title"><b>View Employee Masterlist</b></h1>
    <span class="user-count"><?= $total; ?> Users</span>

    <div class="searchContainer">
        <div class="search-box">
            <?= form_open_multipart('Admin/searchEmp');?>
                <div class="search">    
                    <input type="text" class="searchTerm" name="searchTerm" placeholder="Search for an employee...">
                    <button type="submit" class="searchButton" name="search">
                        <i class="fa fa-search"></i>
                    </button>
                    <!-- Include a clear button for search function -->
                </div>
            <?= form_close();?>
        </div>
    </div>
        
    <div class="table-container">
        <table id="datatable" class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col">Employee Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">ID</th>
                    <th scope="col">Role</th>
                    <th scope="col">Direct Superior</th>
                    <th scope="col">Actions</th>             
                </tr>
            </thead>

            <!--placed a placeholder so it can easily be identified and replaced with php function-->
            <tbody>
                <?php foreach($employees as $employee): ?>
                    <tr>
                        <td data-label="Employee Image">
                            <img
                                <?php if(isset($employee->emp_image)): ?>
                                    class="emp-pic"
                                    src="<?= base_url('./assets/users_image/') . $employee->emp_image; ?>"
                                    alt="employee pic"
                                <?php endif?>
                            >
                        </td>
                        <td class="emp-name-bold" data-label="Device Image"><?=$employee->emp_name; ?></td>
                        <td data-label="ID"><?=$employee->emp_id; ?></td>
                        <td data-label="Role"><?=ucfirst($employee->emp_role); ?></td>
                        <td data-label="Direct Superior"><?=$employee->superior; ?></td>
            
                        <td data-label="Actions">
                            <a href="<?= site_url('Admin/employee_view/') . $employee->id; ?>"><i class="fa fa-solid fa-eye"></i></a>
                            <a href="<?= site_url('Admin/editEmp_view/') . $employee->id;?>"><i class="fas fa-edit" id="edit-btn"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
    
            </tbody>
        </table>
    </div>
    
    <div class="pagination-div">
        <?= $this->pagination->create_links() ?>
    </div>
</div>
