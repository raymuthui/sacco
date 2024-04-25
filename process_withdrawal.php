<?php
session_start();

include('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    exit('User is not logged in.');
}

// Get the withdrawal amount from the form data
$withdraw_amount = $_POST['withdraw_amount'];
$user_id = $_SESSION['login_id'];

// Check if the withdrawal amount is valid (not exceeding the current balance)
$balance_query = $conn->query("SELECT balance FROM savings_account WHERE member_id = $user_id");
$balance_row = $balance_query->fetch_assoc();
$balance = $balance_row['balance'];

if ($withdraw_amount > $balance) {
    echo 'Insufficient balance.';
    exit;
}

// Insert the withdrawal transaction into the transactions table
$insert_transaction = $conn->query("INSERT INTO transactions (account_id, type, amount, transaction_date) 
                                    VALUES ((SELECT id FROM savings_account WHERE member_id = $user_id), 2, $withdraw_amount, NOW())");

if ($insert_transaction) {
    // Update the balance in the savings_account table
    $update_balance = $conn->query("UPDATE savings_account SET balance = balance - $withdraw_amount WHERE member_id = $user_id");

    if ($update_balance) {
        echo 'Withdrawal successful.';
    } else {
        echo 'Failed to update balance.';
    }
} else {
    echo 'Failed to record transaction.';
}
?>
