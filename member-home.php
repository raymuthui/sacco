<?php
session_start();

include('db_connect.php');

// Check if the user is logged in and has a user ID in the session
if (!isset($_SESSION['login_id'])) {
    // Redirect or handle the case where the user is not logged in
    exit('User is not logged in.');
}

// Fetch the user ID from the session
$user_id = $_SESSION['login_id'];

// Fetch member details based on the user ID
$qry = $conn->query("SELECT * FROM members WHERE id = " . $user_id);
$member = $qry->fetch_assoc();

// Check if member details are fetched successfully
if (!$member) {
    // Redirect or handle the case where member details are not found
    exit('Member details not found.');
}
?>

<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom styles for the loan application page */
        .dashboard .card {
            margin-bottom: 20px;
        }

        .dashboard .card-body {
            padding: 20px;
        }

        .dashboard .card-title {
            font-size: 20px;
            font-weight: bold;
        }

        .dashboard .btn-card {
            width: 100%;
        }
    </style>
</head>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card dashboard">
                <div class="card-body">
                    <h5 class="card-title">Apply for a Loan</h5>
                    <form action="" id="loan-application">
                        <input type="hidden" name="borrower_id" value="<?php echo $user_id; ?>">
                        <!-- Other loan application form fields as needed -->
                        <button type="submit" class="btn btn-primary btn-card" id="new_application">Apply</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card dashboard">
                <div class="card-body">
                    <h5 class="card-title">View Current Loan Applications</h5>
                    <a href="index.php?page=loan_applications" class="btn btn-primary btn-card">View Applications</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card dashboard">
                <div class="card-body">
                    <h5 class="card-title">View Current Savings</h5>
                    <a href="index.php?page=loan_applications" class="btn btn-primary btn-card">View Savings</a>
                </div>
            </div>
        </div>
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
                $type = $conn->query("SELECT * FROM loan_types where id in (SELECT loan_type_id from loan_list) ");
                while ($row = $type->fetch_assoc()) {
                    $type_arr[$row['id']] = $row['type_name'];
                }
                $plan = $conn->query("SELECT *,concat(months,' month/s [ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
                while ($row = $plan->fetch_assoc()) {
                    $plan_arr[$row['id']] = $row;
                }
                $qry = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no, b.address from loan_list l inner join borrowers b on b.id = l.borrower_id  order by id asc");
                while ($row = $qry->fetch_assoc()) :
                    $monthly = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage'] / 100))) / $plan_arr[$row['plan_id']]['months'];
                    $penalty = $monthly * ($plan_arr[$row['plan_id']]['penalty_rate'] / 100);
                    $payments = $conn->query("SELECT * from payments where loan_id =" . $row['id']);
                    $paid = $payments->num_rows;
                    $offset = $paid > 0 ? " offset $paid " : "";
                    if ($row['status'] == 2) :
                        $next = $conn->query("SELECT * FROM loan_schedules where loan_id = '" . $row['id'] . "'  order by date(date_due) asc limit 1 $offset ")->fetch_assoc()['date_due'];
                    endif;
                    $sum_paid = 0;
                    while ($p = $payments->fetch_assoc()) {
                        $sum_paid += ($p['amount'] - $p['penalty_amount']);
                    }

                ?>
                    <tr>

                        <td class="text-center"><?php echo $i++ ?></td>
                        <td>
                            <p>Name :<b><?php echo $row['name'] ?></b></p>
                            <p>Contact # :<b><?php echo $row['contact_no'] ?></b></p>
                            <p>Address :<b><?php echo $row['address'] ?></b></p>
                        </td>
                        <td>
                            <p>Reference :<b><?php echo $row['ref_no'] ?></b></p>
                            <p>Loan type :<b><?php echo $type_arr[$row['loan_type_id']] ?></b></p>
                            <p>Plan :<b><?php echo $plan_arr[$row['plan_id']]['plan'] ?></b></p>
                            <p>Amount :<b><?php echo $row['amount'] ?></b></p>
                            <p>Total Payable Amount :<b><?php echo number_format($monthly * $plan_arr[$row['plan_id']]['months'], 2) ?></b></p>
                            <p>Monthly Payable Amount: <b><?php echo number_format($monthly, 2) ?></b></p>
                            <p>Overdue Payable Amount: <b><?php echo number_format($penalty, 2) ?></b></p>
                            <?php if ($row['status'] == 2 || $row['status'] == 3) : ?>
                                <p>Date Released: <b><?php echo date("M d, Y", strtotime($row['date_released'])) ?></b></p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 2) : ?>
                                <p>Date: <b>
                                        <?php echo date('M d, Y', strtotime($next)); ?>
                                    </b></p>
                                <p>Monthly amount:<b><?php echo number_format($monthly, 2) ?></b></p>
                                <p>Penalty :<b><?php echo $add = (date('Ymd', strtotime($next)) < date("Ymd")) ?  $penalty : 0; ?></b></p>
                                <p>Payable Amount :<b><?php echo number_format($monthly + $add, 2) ?></b></p>
                            <?php else : ?>
                                N/a
                            <?php endif; ?>
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
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#loan-list').dataTable()
    $('#new_application').click(function() {
        // Prefill borrower name in the form
        $('#borrower_name').val('<?php echo $member["lastname"] ?>, <?php echo $member["firstname"] ?> <?php echo $member["middlename"] ?>');
        // Show the modal
        uni_modal("New Loan Application", "manage_loan.php", 'mid-large')
    })

    $('.edit_loan').click(function() {
        uni_modal("Edit Loan", "manage_loan.php?id=" + $(this).attr('data-id'), 'mid-large')
    })
    $('.delete_loan').click(function() {
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
    // $(document).ready(function() {
    //     $('#loan-application').submit(function(e) {
    //         e.preventDefault();
    //         $.ajax({
    //             url: 'ajax.php?action=save_loan',
    //             method: 'POST',
    //             data: $(this).serialize(),
    //             success: function(resp) {
    //                 if (resp == 1) {
    //                     alert("Loan Data successfully saved.");
    //                     location.reload();
    //                 }
    //             }
    //         });
    //     });
    // });
</script>