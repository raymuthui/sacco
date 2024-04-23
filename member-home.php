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

$loan_qry = $conn->query("SELECT amount FROM loan_list WHERE member_id = " . $user_id);
$loan_amount = $loan_qry->fetch_assoc();

$loan__qry = $conn->query("SELECT penalty_accrued FROM loan_list WHERE member_id = " . $user_id);
$loan_penalty = $loan__qry->fetch_assoc();

$news_qry = $conn->query("SELECT * FROM news");
$news = $news_qry->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/872ee97990.js" crossorigin="anonymous"></script>
    <title>Member Home</title>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        header {
            background-color: white;
            padding: 1rem 2rem;
        }

        main {
            padding: 0 2rem;
            height: calc(100vh - 7rem);
        }
    </style>
</head>

<body class="bg-[#f2efef]">
  <?php include 'member-header.php' ?>

    <main class="d-flex flex-column px-12">
        <div class="d-flex flex-row justify-content-between pt-5 gap-5">
            <div class="bg-white w-50 rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                <h2 class="font-semibold text-xl">Profile Overview</h2>
                <div class="d-flex flex-row bg-[#f2efef] rounded-lg">
                    <div class="flex place-items-center m-2 p-2 rounded-md bg-white" style="width: 200px; height: 150px; border: 1px solid blue;">
                        <img class="image-fluid" src="<?php echo $baseurl. '/' . $member['profile_pic_path'] ?>" alt="profile pic">
                    </div>
                    <div class="rounded-md bg-white m-2 p-2 w-full">
                        <p>Name: <?php echo $member['firstname'] . ' ' . $member['middlename'] . ' ' . $member['lastname'] ?></p>
                        <p>Total Loan Amount: Ksh <?php echo $loan_amount['amount'] ? $loan_amount['amount'] : 0; ?></p>
                        <p>Total Investments:</p>
                        <p>Total Penalty: Ksh <?php echo $loan_penalty['penalty_accrued'] ? $loan_penalty['penalty_accrued'] : 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white w-50 rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                <h2 class="font-semibold text-xl">Upcoming Sacco News</h2>
                <div class="d-flex flex-row bg-[#f2efef] rounded-lg">
                    <div class="flex place-items-center m-2 p-2 rounded-md bg-white" style="width: 200px; height: 150px; border: 1px solid blue;">
                        <img class="image-fluid" src="<?php echo $baseurl. '/' . $news['article_image_path'] ?>" alt="article pic">
                    </div>
                    <div class="rounded-md bg-white m-2 p-2 w-full">
                        <p>Article Title: <?php echo $news['article_title'] ? $news['article_title'] : 'N/A'?></p>
                        <p>Article Content: <?php echo $news['article_content'] ? $news['article_content'] : 'N/A'; ?></p>
                        <p>Date Created: <?php echo $news['date_created'] ? $news['date_created'] : 'N/A'; ?></p>
                    </div>
                </div>
                <br>
                <a href="member-news.php">View More</a>
            </div>
        </div>
        <div class="row pt-7 h-50">
            <div class="col-md-4">
                <div class="bg-white rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                    <h2 class="font-semibold text-xl">Investments</h2>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a href="investments.html">View More</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                    <h2 class="font-semibold text-xl">Savings Account</h2>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a href="account.html">View More</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                    <h2 class="font-semibold text-xl">Loans and Repayment</h2>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a href="member-loan.php">View More</a>
                </div>
            </div>
        </div>
    </main>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>