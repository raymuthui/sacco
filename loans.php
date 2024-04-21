<?php include 'db_connect.php' ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <large class="card-title">
                    <b>Loan List</b>
                </large>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="loan-list">
                    <colgroup>
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Borrower</th>
                            <th class="text-center">Loan Details</th>
                            <th class="text-center">Next Payment Details</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;

                        // Fetch all loans along with member details
                        $qry = $conn->query("SELECT l.*, m.firstname, m.middlename, m.lastname, m.contact_no, m.address, lt.type_name, lt.interest_percentage, lt.penalty_rate, lt.months
                                                FROM loan_list l 
                                                INNER JOIN members m ON l.member_id = m.id 
												INNER JOIN loan_types lt ON l.loan_type_id = lt.id
                                                ORDER BY l.id ASC");

                        while ($row = $qry->fetch_assoc()) :
							// Calculate payment details
							$monthly = ($row['amount'] + ($row['amount'] * ($row['interest_percentage'] / 100))) / $row['months'];
							$penalty = $monthly * ($row['penalty_rate'] / 100);
							//$next_payment = $monthly + ($row['overdue'] == 1 ? $penalty : 0);
							$total_payable = $monthly * $row['months'];
							$payableAmount = $monthly + $penalty
                            // Display loan details for each loan
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td>
                                    <p>Name: <b><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] ?></b></p>
                                    <p>Contact #: <b><?php echo $row['contact_no'] ?></b></p>
                                    <p>Address: <b><?php echo $row['address'] ?></b></p>
                                </td>
                                <td>
                                    <p>Reference: <b><?php echo $row['ref_no'] ?></b></p>
                                    <!-- Add other loan details as needed -->
									<p>Loan type: <b><?php echo $row['type_name'] ?></b></p>
									<p>Amount: <b><?php echo $row['amount'] ?></b></p>
                                    <p>Total Payable Amount: <b><?php echo number_format($total_payable, 2) ?></b></p>
                                    <p>Monthly Payable Amount: <b><?php echo number_format($monthly, 2) ?></b></p>
                                    <p>Overdue Payable Amount: <b><?php echo number_format($penalty, 2) ?></b></p>
									<?php if ($row['status'] == 2 || $row['status'] == 3) : ?>
										<p>Date Released: <b><?php echo date("M d, Y", strtotime($row['date_released'])) ?></b></p>
									<?php endif; ?>
									<p>Date Created: <b><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
                                </td>
                                <td>
                                    <?php
									if ($row['status'] == 1 ||$row['status'] == 2 || $row['status'] == 3) {
										// Check if there are payments for this loan
										$payments_qry = $conn->query("SELECT * FROM payments WHERE loan_id = " . $row['id']);
										if ($payments_qry->num_rows > 0) {
											$payment_row = $payments_qry->fetch_assoc();
											// Display payment details
											echo "<p>Payment ID: <b>{$payment_row['id']}</b></p>";
											echo "<p>Payment Date: <b>{$payment_row['date_created']}</b></p>";
											echo "<p>Amount Paid: <b>{$payment_row['amount']}</b></p>";
											echo "<p>Penalty Amount: <b>{$payment_row['penalty_amount']}</b></p>";
											echo "<p>Overdue: <b>" . ($payment_row['overdue'] ? 'Yes' : 'No') . "</b></p>";
										} else {
											echo "<p>No payments made yet.</p>";
										} 
									} else if ($row['status'] == 0) {
										echo "<p>Loan not approved yet.</p>";
									} else if ($row['status'] == 4) {
										echo "<p>Loan denied.</p>";
									} else {
										echo "<p>Invalid loan status.</p>";
									}
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 0) : ?>
                                        <span class="badge badge-warning">For Approval</span>
                                    <?php elseif ($row['status'] == 1) : ?>
                                        <span class="badge badge-info">Approved</span>
                                    <?php elseif ($row['status'] == 2) : ?>
                                        <span class="badge badge-primary">Released</span>
                                    <?php elseif ($row['status'] == 3) : ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php elseif ($row['status'] == 4) : ?>
                                        <span class="badge badge-danger">Denied</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary edit_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger delete_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
                                </td>
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

    td img {
        width: 8vw;
        height: 12vh;
    }

    td {
        vertical-align: middle !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $('#loan-list').dataTable()
    $('.edit_loan').click(function() {
        console.log("Edit button pressed");
        uni_modal("Edit Loan", "manage_loan.php?id=" + $(this).attr('data-id'), 'mid-large')
    })
    $('.delete_loan').click(function() {
        console.log("Delete button pressed");
        _conf("Are you sure to delete this data?", "delete_loan", [$(this).attr('data-id')])
    })

    function delete_loan($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_loan',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Loan successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }
</script>
