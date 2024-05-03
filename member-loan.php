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
$loan_qry = $conn->query("SELECT ll.amount, ll.status, ll.penalty_accrued, ll.ref_no, ll.purpose, lt.type_name, lt.description, lt.months, lt.interest_percentage 
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
$default_status = 0;

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
    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
    </div>
    <main class="container">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            My Loans
                            <button class="btn btn-primary " type="button" id="new_application"><i class="fa fa-plus"></i> Create New Application</button>
                            <button class="btn btn-primary " type="button" id="new_payments"><i class="fa fa-plus"></i> Make Payment</button>
                            <button class="btn btn-primary " type="button" id="new_payments"><i class="fa-solid fa-caret-down"></i> View Payment History</button>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll;">
                            <?php
                            // Loop through all the user's loans and display their details
                            foreach ($loan_info as $loan) {
                            ?>
                                <?php $amount = $loan['amount']; $interest = $loan['interest_percentage']; $months = $loan['months'];?>
                                <td>
                                    <h5 class="card-title">Reference No: <?php echo isset($loan['ref_no']) ? $loan['ref_no'] : 'N/A'; ?></h5>
                                    <p class="card-text">Amount: Ksh <?php echo isset($loan['amount']) ? $loan['amount'] : 'N/A'; ?></p>
                                    <p class="card-text">Type Name: <?php echo isset($loan['type_name']) ? $loan['type_name'] : 'N/A'; ?></p>
                                    <p class="card-text">Description: <?php echo isset($loan['description']) ? $loan['description'] : 'N/A'; ?></p>
                                    <p class="card-text">Status: <?php if ($loan['status'] == 0) : ?>
                                            <span class="badge badge-warning">For Approval</span>
                                        <?php elseif ($loan['status'] == 1) : ?>
                                            <span class="badge badge-info">Approved</span>
                                        <?php elseif ($loan['status'] == 2) : ?>
                                            <span class="badge badge-primary">Released</span>
                                        <?php elseif ($loan['status'] == 3) : ?>
                                            <span class="badge badge-success">Completed</span>
                                        <?php elseif ($loan['status'] == 4) : ?>
                                            <span class="badge badge-danger">Denied</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="card-text">Purpose: <?php echo isset($loan['purpose']) ? $loan['purpose'] : 'N/A'; ?></p>
                                    <p class="card-text">Months: <?php echo isset($loan['months']) ? $loan['months'] : 'N/A'; ?></p>
                                    <p class="card-text">Monthly Installments: <?php echo number_format((($amount + ($amount * ($interest/100))) / $months), 2);
 ?></p>
                                    <p class="card-text">Interest Percentage: <?php echo isset($loan['interest_percentage']) ? $loan['interest_percentage'] : 'N/A'; ?>%</p>
                                    <hr>
                                    <br>
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
                                <br>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for payment -->
        <div id="preloader"></div>
        <div class="modal fade" id="uni_modal" role='dialog'>
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                                    <?php
                                    // Include your database connection file
                                    include 'dbconnect.php';

                                    // Perform the database query to fetch loan types
                                    $result = $conn->query("SELECT * FROM loan_types");

                                    // Check if the query was successful
                                    if ($result && $result->num_rows > 0) {
                                        // Loop through each row to generate options
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['id'] . '">' . $row['type_name'] . '</option>';
                                        }
                                    } else {
                                        // No rows found or query failed
                                        echo '<option value="">No loan types found</option>';
                                    }

                                    // Close database connection
                                    $conn->close();
                                    ?>
                                </select>

                            </div>
                            <!-- <div class="form-group">
                                <label>Months</label>
                                <input type="number" class="form-control" id="months" readonly>
                            </div> -->
                            <div class="form-group">
                                <label for="amount">Loan Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter loan amount">
                            </div>
                            <!-- <div class="form-group">
                                <label>Monthly Installment</label>
                                <input type="number" class="form-control" id="repaymentAmount" name="installment_amount" readonly>
                            </div>-->
                            <div class="form-group"> 
                                <label for="purpose">Purpose</label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="3"></textarea>
                            </div>
                            <input type="hidden" id="interest_rate">
                            <!-- Hidden input field for member_id -->
                            <input type="hidden" name="member_id" value="<?php echo $user_id; ?>">
                            <!-- Hidden input field for status with default value of 0 -->
                            <input type="hidden" name="status" id="status" value="<?php echo $default_status; ?>">
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/venobox/venobox.min.js"></script>
    <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/vendor/counterup/counterup.min.js"></script>
    <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="assets/js/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="assets/font-awesome/js/all.min.js"></script>
    <!-- <script type="text/javascript" src="assets/font-awesome/js/font.js"></script> -->
    <script type="text/javascript" src="assets/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
    <script>
        window.start_load = function() {
            $('body').prepend('<di id="preloader2"></di>')
        }
        window.end_load = function() {
            $('#preloader2').fadeOut('fast', function() {
                $(this).remove();
            })
        }
        window.uni_modal = function($title = '', $url = '', $size = "") {
            start_load()
            $.ajax({
                url: $url,
                error: err => {
                    console.log()
                    alert("An error occured")
                },
                success: function(resp) {
                    if (resp) {
                        $('#uni_modal .modal-title').html($title)
                        $('#uni_modal .modal-body').html(resp)
                        if ($size != '') {
                            $('#uni_modal .modal-dialog').addClass($size)
                        } else {
                            $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                        }
                        $('#uni_modal').modal('show')
                        end_load()
                    }
                }
            })
        }
        window.alert_toast = function($msg = 'TEST', $bg = 'success') {
            $('#alert_toast').removeClass('bg-success')
            $('#alert_toast').removeClass('bg-danger')
            $('#alert_toast').removeClass('bg-info')
            $('#alert_toast').removeClass('bg-warning')

            if ($bg == 'success')
                $('#alert_toast').addClass('bg-success')
            if ($bg == 'danger')
                $('#alert_toast').addClass('bg-danger')
            if ($bg == 'info')
                $('#alert_toast').addClass('bg-info')
            if ($bg == 'warning')
                $('#alert_toast').addClass('bg-warning')
            $('#alert_toast .toast-body').html($msg)
            $('#alert_toast').toast({
                delay: 3000
            }).toast('show');
        }
        $(document).ready(function() {
            $('#preloader').fadeOut('fast', function() {
                $(this).remove();
            })
        })
        // Get input elements
        // var monthsInput = document.getElementById('months');
        // var amountInput = document.getElementById('amount');
        // var repaymentInput = document.getElementById('repaymentAmount');

        // // Add event listeners to listen for changes in loan amount or months
        // amountInput.addEventListener('input', calculateRepayment);
        // monthsInput.addEventListener('input', calculateRepayment);

        // // Function to calculate repayment amount
        // function calculateRepayment() {
        //     var months = parseInt(monthsInput.value);
        //     var amount = parseFloat(amountInput.value);

        //     // Check if months and amount are valid numbers
        //     if (!isNaN(months) && !isNaN(amount)) {
        //         // Calculate repayment amount
        //         var repayment = (amount + (amount * interest_rate)) / months;

        //         // Display repayment amount with two decimal places
        //         repaymentInput.value = repayment.toFixed(2);
        //     } else {
        //         // If either input is not a valid number, display an error message
        //         repaymentInput.value = 'Invalid input';
        //     }
        // }

        $(document).ready(function() {


            //TODO: create a payments portal for the sacco
            $('#new_payments').click(function() {
                console.log('Payments button pressed');
                uni_modal("New Payment", "manage_payment.php", 'mid-large')
            })
            // Button click event to open the new loan modal
            $('#new_application').click(function() {
                $('#newLoanModal').modal('show');
            });
            // $('#loanType').change(function() {
            //     var typeName = $(this).val(); // Get the selected loan type name
            //     $.ajax({
            //         url: 'ajax.php?action=get_loan_type_months', // URL of your PHP script to fetch months
            //         type: 'POST',
            //         data: {
            //             type_name: typeName
            //         }, // Pass the selected loan type name as data
            //         success: function(response) {
            //             $('#months').val(response); // Update the months input field with the response
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //             // Handle errors here
            //         }
            //     });
            // });


            // Submit loan application form
            $('#submitLoan').click(function() {
                var formData = $('#loan-application-form').serialize();
                $.ajax({
                    url: 'ajax.php?action=save_loan',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response == 1) {
                            alert_toast('Loan application submitted successfully.');
                            $('#newLoanModal').modal('hide');
                            // Refresh or update loan information display here
                            setTimeout(function() {
                                location.reload();
                            }, 1500)
                        } else {
                            alert_toast('Failed to submit loan application.', 'danger');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>