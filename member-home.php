<?php
include('db_connect.php');

// Check if the user is logged in and has a user ID in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect or handle the case where the user is not logged in
    exit('User is not logged in.');
}

// Fetch the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch member details based on the user ID
$qry = $conn->query("SELECT * FROM members WHERE id = " . $user_id);
$member = $qry->fetch_assoc();

// Check if member details are fetched successfully
if (!$member) {
    // Redirect or handle the case where member details are not found
    exit('Member details not found.');
}
?>

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

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card dashboard">
                <div class="card-body">
                    <h5 class="card-title">Apply for a Loan</h5>
                    <form action="" id="loan-application">
                        <input type="hidden" name="borrower_id" value="<?php echo $user_id; ?>">
                        <!-- Other loan application form fields as needed -->
                        <button type="submit" class="btn btn-primary btn-card">Apply</button>
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
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#loan-application').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax.php?action=save_loan',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert("Loan Data successfully saved.");
                        location.reload();
                    }
                }
            });
        });
    });
</script>
