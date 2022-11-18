<div class="user-container">

    <h1 class="page-title"><b>Device Logs</b></h1>
        
    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col">Device ID</th>
                    <th scope="col">Device Name</th>
                    <th scope="col">RFID</th>
                    <th scope="col">Date Deployed</th>
                    <th scope="col">Date Returned</th>         
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
        $("#logs").load("<?php echo base_url('Admin/dev_logs_table')?>", function(){
            setTimeout(refreshTable, 2000);
        });
    }
</script>