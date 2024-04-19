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
while($row = $loan_qry->fetch_assoc()) {
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
    <title>Loan Information</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Loan Information</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        My Loans
                    </div>
                    <div class="card-body">
                        <?php
                        // Loop through all the user's loans and display their details
                        foreach ($loan_info as $loan) {
                            ?>
                            <h5 class="card-title">Reference No: <?php echo isset($loan['ref_no']) ? $loan['ref_no'] : 'N/A'; ?></h5>
                            <p class="card-text">Amount: Ksh <?php echo isset($loan['amount']) ? $loan['amount'] : 'N/A'; ?></p>
                            <p class="card-text">Type Name: <?php echo isset($loan['type_name']) ? $loan['type_name'] : 'N/A'; ?></p>
                            <p class="card-text">Description: <?php echo isset($loan['description']) ? $loan['description'] : 'N/A'; ?></p>
                            <p class="card-text">Months: <?php echo isset($loan['months']) ? $loan['months'] : 'N/A'; ?></p>
                            <p class="card-text">Interest Percentage: <?php echo isset($loan['interest_percentage']) ? $loan['interest_percentage'] : 'N/A'; ?>%</p>
                            <hr>
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
                    <div class="card-body">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
