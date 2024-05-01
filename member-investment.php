<?php
session_start();

include('db_connect.php'); // Assuming this file contains your database connection code

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

// Fetch investment types
$investment_types_qry = $conn->query("SELECT * FROM investment_types");
$investment_types = array();

while ($row = $investment_types_qry->fetch_assoc()) {
    $investment_types[] = $row;
}

// Fetch member's investments
$investments_qry = $conn->query("SELECT il.id, il.ref_no, it.investment_name, il.amount, it.months, it.interest_rate
                                  FROM investment_list il
                                  INNER JOIN investment_types it ON il.investment_type_id = it.id
                                  WHERE il.member_id = " . $user_id);

$investments = array();

while ($row = $investments_qry->fetch_assoc()) {
    $investments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investments Information</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/872ee97990.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include 'member-header.php' ?>
    <main class="container">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            My Investments
                            <button class="btn btn-primary col-md-3 float-right" type="button" id="new_investment"><i class="fa fa-plus"></i> Make New Investment</button>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll;">
                            <?php
                            // Loop through all the user's investments and display their details
                            foreach ($investments as $investment) {
                            ?>
                                <td>
                                    <!-- You can add buttons or actions related to each investment here -->
                                </td>
                                <td>
                                    <h5 class="card-title">Reference No: <?php echo isset($investment['ref_no']) ? $investment['ref_no'] : 'N/A'; ?></h5>
                                    <p class="card-text">Investment Type: <?php echo isset($investment['investment_name']) ? $investment['investment_name'] : 'N/A'; ?></p>
                                    <p class="card-text">Amount: <?php echo isset($investment['amount']) ? $investment['amount'] : 'N/A'; ?></p>
                                    <p class="card-text">Months: <?php echo isset($investment['months']) ? $investment['months'] : 'N/A'; ?></p>
                                    <p class="card-text">Interest Rate: <?php echo isset($investment['interest_rate']) ? $investment['interest_rate'] : 'N/A'; ?>%</p>
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
                            Investment Types
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll;">
                            <?php
                            // Loop through all investment types and display their details
                            foreach ($investment_types as $type) {
                            ?>
                                <h5 class="card-title">Investment Name: <?php echo $type['investment_name']; ?></h5>
                                <p class="card-text">Minimum Amount: <?php echo $type['min_amount']; ?></p>
                                <p class="card-text">Interest Rate: <?php echo $type['interest_rate']; ?>%</p>
                                <p class="card-text">Months: <?php echo $type['months']; ?></p>
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
        <!-- Add your modal for new investment here similar to the loan modal -->
        <!-- Modal for New Investment -->
        <div class="modal fade" id="newInvestmentModal" tabindex="-1" role="dialog" aria-labelledby="newInvestmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newInvestmentModalLabel">New Investment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="investment-form">
                            <div class="form-group">
                                <label for="investmentType">Investment Type</label>
                                <select class="form-control" id="investmentType" name="investment_type_id">
                                    <!-- Options will be populated dynamically using PHP -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Investment Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter investment amount" min="0">
                            </div>
                            <!-- Hidden input field for member_id -->
                            <input type="hidden" name="member_id" value="<?php echo $user_id; ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitInvestment">Submit Investment</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Include Bootstrap and other JS libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Button click event to open the new investment modal
            $('#new_investment').click(function() {
                $('#newInvestmentModal').modal('show');
                // Fetch investment types dynamically and populate the select input
                $.ajax({
                    url: 'ajax.php?action=get_investment_types',
                    method: 'GET',
                    success: function(response) {
                        try {
                            var types = JSON.parse(response);
                            var options = '';
                            types.forEach(function(type) {
                                options += '<option value="' + type.id + '">' + type.investment_name + '</option>';
                            });
                            $('#investmentType').html(options);
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

            function validateAmount() {
                var selectedInvestmentType = $('#investmentType').val();
                var minAmount = parseFloat($('#investmentType option:selected').data('min-amount'));
                var amount = parseFloat($('#amount').val());

                if (amount < minAmount) {
                    alert('Amount cannot be less than the minimum amount for this investment type.');
                    return false;
                }
                return true;
            }
            // Submit investment form
            $('#submitInvestment').click(function() {
                if (validateAmount()) {
                    var formData = $('#investment-form').serialize();
                    $.ajax({
                        url: 'ajax.php?action=save_investment',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response == 1) {
                                alert('Investment submitted successfully.');
                                $('#newInvestmentModal').modal('hide');
                                // Refresh or update investment information display here
                                setTimeout(function() {
                                    location.reload();
                                }, 1500)
                            } else {
                                alert(response);
                            }
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
