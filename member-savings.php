<?php 
session_start();

include ('db_connect.php');

$baseurl = "http://localhost/sacco";

// Check if the user is logged in and has a user ID in the session
if (!isset($_SESSION['login_id'])) {
    // Redirect or handle the case where the user is not logged in
    exit('User is not logged in.');
}


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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/872ee97990.js" crossorigin="anonymous"></script>
    <title>Members Savings</title>
</head>

<body>
    <?php include 'member-header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!-- Display Account Balance -->
                <div class="card">
                    <div class="card-header">
                        <h4>Account Balance</h4>
                    </div>
                    <div class="card-body">
                        <!-- PHP code to fetch and display account balance -->
                        <?php
                        $balance_query = $conn->query("SELECT balance FROM savings_account WHERE member_id = $user_id");
                        $balance_row = $balance_query->fetch_assoc();
                        $balance = $balance_row['balance'];
                        ?>
                        <p>Your current balance: <?php echo number_format($balance, 2) ?></p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Deposit</h4>
                            </div>
                            <div class="card-body">
                                <form id="deposit_form">
                                    <div class="form-group">
                                        <label for="deposit_amount">Amount to Deposit:</label>
                                        <input type="number" step="100" min="100" class="form-control" id="deposit_amount" name="deposit_amount" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Deposit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Withdrawal</h4>
                            </div>
                            <div class="card-body">
                                <form id="withdraw_form">
                                    <div class="form-group">
                                        <label for="withdraw_amount">Amount to Withdraw:</label>
                                        <input type="number" step="100" min="100" class="form-control" id="withdraw_amount" name="withdraw_amount" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Withdraw</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Recent Transactions -->
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Transactions</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="transaction-list">
                            <colgroup>
                                <col width="10%">
                                <col width="20%">
                                <col width="25%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Transaction Type</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Transaction Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $transactions_query = $conn->query("SELECT * FROM transactions WHERE account_id IN (SELECT id FROM savings_account WHERE member_id = $user_id) ORDER BY transaction_date DESC LIMIT 5");
                                $counter = 1;
                                while ($transaction_row = $transactions_query->fetch_assoc()) :
                                ?>
                                   <tr style="background-color: <?php echo $transaction_row['type'] == 1 ? 'lightgreen' : 'salmon' ?>">
                                        <td class="text-center"><?php echo $counter++ ?></td>
                                        <td>
                                            <?php echo $transaction_row['type'] == 1 ? 'Deposit' : 'Withdrawal' ?>
                                        </td>
                                        <td><?php echo number_format($transaction_row['amount'], 2) ?></td>
                                        <td><?php echo $transaction_row['transaction_date'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Deposit and Withdraw Forms
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Deposit</h4>
                    </div>
                    <div class="card-body">
                        <form id="deposit_form">
                            <div class="form-group">
                                <label for="deposit_amount">Amount to Deposit:</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="deposit_amount" name="deposit_amount" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Deposit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Withdrawal</h4>
                    </div>
                    <div class="card-body">
                        <form id="withdraw_form">
                            <div class="form-group">
                                <label for="withdraw_amount">Amount to Withdraw:</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="withdraw_amount" name="withdraw_amount" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Withdraw</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // jQuery AJAX for deposit and withdrawal forms
        $(document).ready(function () {
            $('#deposit_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'process_deposit.php', // PHP script to process deposit
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response); // Show success or error message
                        // Reload or update account balance and transactions
                        setTimeout(function(){
                                location.reload();
                            },1500)
                    }
                });
            });

            $('#withdraw_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'process_withdrawal.php', // PHP script to process withdrawal
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response); // Show success or error message
                        // Reload or update account balance and transactions
                        setTimeout(function(){
                                location.reload();
                            },1500)
                    }
                });
            });
        });
    </script>
</body>

</html>
