<div class="user-container">

    <h1 class="page-title"><b>Employee Logs</b></h1>
        
    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col">Employee ID</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">RFID</th>
                    <th scope="col">Time Deployed</th>
                    <th scope="col">Time Returned</th>         
                </tr>
            </thead>
            <tbody id="logs">
        
            </tbody>
        </table>
    </div>
    
</div>

<script>
    $(document).ready(function(){
        refreshTable();
    });

    function refreshTable(){
        $("#logs").load("<?php echo base_url('Admin/emp_logs_table')?>", function(){
            setTimeout(refreshTable, 2000);
        });
    }
</script>