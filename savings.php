<?php include 'db_connect.php' ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><b>Savings Account List</b></h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="savings-list">
                    <colgroup>
                        <col width="15%">
                        <col width="25%">
                        <col width="35%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Account Number</th>
                            <th class="text-center">Member Details</th>
                            <th class="text-center">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;

                        // Fetch all savings accounts along with member details
                        $qry = $conn->query("SELECT sa.*, m.firstname, m.middlename, m.lastname, m.contact_no, m.address
                                                FROM savings_account sa 
                                                INNER JOIN members m ON sa.member_id = m.id 
                                                ORDER BY sa.id ASC");

                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td><?php echo $row['account_no'] ?></td>
                                <td>
                                    <p>Name: <b><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] ?></b></p>
                                    <p>Contact #: <b><?php echo $row['contact_no'] ?></b></p>
                                    <p>Address: <b><?php echo $row['address'] ?></b></p>
                                </td>
                                <td class="text-right"><?php echo number_format($row['balance'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    td p {
        margin: unset;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#savings-list').DataTable();
    });
</script>
