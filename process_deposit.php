<?php
session_start();

include('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    exit('User is not logged in.');
}

// Get the deposit amount from the form data
$deposit_amount = $_POST['deposit_amount'];
$user_id = $_SESSION['login_id'];

// Insert the deposit transaction into the transactions table
$insert_transaction = $conn->query("INSERT INTO transactions (account_id, type, amount, transaction_date) 
                                    VALUES ((SELECT id FROM savings_account WHERE member_id = $user_id), 1, $deposit_amount, NOW())");

if ($insert_transaction) {
    // Update the balance in the savings_account table
    $update_balance = $conn->query("UPDATE savings_account SET balance = balance + $deposit_amount WHERE member_id = $user_id");

    if ($update_balance) {
        echo 'Deposit successful.';
    } else {
        echo 'Failed to update balance.';
    }
} else {
    echo 'Failed to record transaction.';
}
?>
