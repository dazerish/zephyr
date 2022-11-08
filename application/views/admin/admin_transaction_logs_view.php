
<script>
    let click = document.querySelector('.regbtn');
    let list = document.querySelector('.list');
    click.addEventListener("click", ()=> {
        list.classList.toggle('newList');
    });
</script>

<div class="user-container">

    <h1 class="page-title"><b>Transaction Logs</b></h1>

    <div class="table-container">
        <table class="table-responsive">
            <thead>
                <tr class="user-details">
                    <th scope="col">Transaction Status</th>
                    <th scope="col">Device ID</th>
                    <th scope="col">Device Name</th>
                    <th scope="col">Borrower</th>
                    <th scope="col">Reserved Date</th>
                    <th scope="col">Return Date</th>           
                </tr>
            </thead>

            <!--placed a placeholder so it can easily be identified and replaced with php function-->
            <tbody>
                <?php foreach ($transactions as $transaction) : ?>
                    <tr class="align-middle">
                        <td data-label="Transaction Status"><?= $transaction->transaction_status; ?></td>
                        <td data-label="Device ID"><?= $transaction->borrowedDev_id;?></td>
                        <td data-label="Device Name"><?= $transaction->borrowedDev_name; ?></td>
                        <td data-label="Borrower"><?= $transaction->borrower; ?></td>
                        <td data-label="Reserved Date"><?= $transaction->decision_time; ?></td>
                        <td data-label="Return Date"><?= $transaction->return_date; ?></td> 
                    </tr>
                <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
    

    <?= $this->pagination->create_links(); ?>
</div>