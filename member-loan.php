<?php
session_start();

include('db_connect.php');

$baseurl = "http://localhost/sacco";

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

// Fetch loan information with loan type details for the specific user
$loan_qry = $conn->query("SELECT ll.amount, ll.penalty_accrued, ll.ref_no, lt.type_name, lt.description, lt.months, lt.interest_percentage 
                            FROM loan_list ll 
                            INNER JOIN loan_types lt ON ll.loan_type_id = lt.id 
                            WHERE ll.member_id = " . $user_id);

$loan_info = array();

// Loop through all of the user's loans
while ($row = $loan_qry->fetch_assoc()) {
    $loan_info[] = $row;
}

$loan_types = $conn->query("SELECT * FROM loan_types");
$types_info = array(); // Initialize an array to store all loan types

// Loop through all loan types and store them in the $types_info array
while ($row = $loan_types->fetch_assoc()) {
    $types_info[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/872ee97990.js" crossorigin="anonymous"></script>
    <title>Loan Information</title>
</head>

<body>
    <?php include 'member-header.php' ?>
    <main class="container">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            My Loans
                            <button class="btn btn-primary col-md-3 float-right" type="button" id="new_application"><i class="fa fa-plus"></i> Create New Application</button>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll;">
                            <?php
                            // Loop through all the user's loans and display their details
                            foreach ($loan_info as $loan) {
                            ?>
                                <td>
                                    <button class="btn btn-primary  col-md-2 float-right" type="button" id="new_payments"><i class="fa fa-plus"></i> Make Payment</button>
                                </td>
                                <td>
                                    <h5 class="card-title">Reference No: <?php echo isset($loan['ref_no']) ? $loan['ref_no'] : 'N/A'; ?></h5>
                                    <p class="card-text">Amount: Ksh <?php echo isset($loan['amount']) ? $loan['amount'] : 'N/A'; ?></p>
                                    <p class="card-text">Type Name: <?php echo isset($loan['type_name']) ? $loan['type_name'] : 'N/A'; ?></p>
                                    <p class="card-text">Description: <?php echo isset($loan['description']) ? $loan['description'] : 'N/A'; ?></p>
                                    <p class="card-text">Months: <?php echo isset($loan['months']) ? $loan['months'] : 'N/A'; ?></p>
                                    <p class="card-text">Interest Percentage: <?php echo isset($loan['interest_percentage']) ? $loan['interest_percentage'] : 'N/A'; ?>%</p>
                                    <hr>
                                </td>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Loan Types
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll;">
                            <?php
                            // Loop through all loan types and display their details
                            foreach ($types_info as $type) {
                            ?>
                                <h5 class="card-title">Type Name: <?php echo $type['type_name']; ?></h5>
                                <p class="card-text">Description: <?php echo $type['description']; ?></p>
                                <p class="card-text">Miniumum Amount: <?php echo $type['min_amount']; ?></p>
                                <p class="card-text">Maximum Amount: <?php echo $type['max_amount']; ?></p>
                                <p class="card-text">Months: <?php echo $type['months']; ?></p>
                                <p class="card-text">Interest Percentage: <?php echo $type['interest_percentage']; ?>%</p>
                                <p class="card-text">Penalty Rate: <?php echo $type['penalty_rate']; ?>%</p>
                                <hr>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for New Loan Application -->
        <div class="modal fade" id="newLoanModal" tabindex="-1" role="dialog" aria-labelledby="newLoanModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newLoanModalLabel">New Loan Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="loan-application-form">
                            <div class="form-group">
                                <label for="loanType">Loan Type</label>
                                <select class="form-control" id="loanType" name="loan_type_id">
                                    <!-- Options will be populated dynamically using PHP -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Loan Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter loan amount">
                            </div>
                            <div class="form-group">
                                <label for="purpose">Purpose</label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="3"></textarea>
                            </div>
                            <!-- Hidden input field for member_id -->
                            <input type="hidden" name="member_id" value="<?php echo $user_id; ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitLoan">Submit Application</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#loan-list').dataTable()
            $('#new_payments').click(function() {
                uni_modal("New Payment", "manage_payment.php", 'mid-large')
            })
            // Button click event to open the new loan modal
            $('#new_application').click(function() {
                $('#newLoanModal').modal('show');
                // Fetch loan types dynamically and populate the select input
                $.ajax({
                    url: 'ajax.php?action=get_loan_types',
                    method: 'GET',
                    success: function(response) {
                        try {
                            var types = JSON.parse(response);
                            var options = '';
                            types.forEach(function(type) {
                                options += '<option value="' + type.id + '">' + type.type_name + '</option>';
                            });
                            $('#loanType').html(options);
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            // Handle the error, e.g., display a message to the user or log it
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        // Handle the AJAX error, e.g., display a message to the user or log it
                    }
                });

            });

            // Submit loan application form
            $('#submitLoan').click(function() {
                var formData = $('#loan-application-form').serialize();
                $.ajax({
                    url: 'ajax.php?action=save_loan',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response == 1) {
                            alert('Loan application submitted successfully.');
                            $('#newLoanModal').modal('hide');
                            // Refresh or update loan information display here
                            setTimeout(function() {
                                location.reload();
                            }, 1500)
                        } else {
                            alert('Failed to submit loan application.');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>