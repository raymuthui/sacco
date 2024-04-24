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
$status = 2;

$loan_qry = $conn->query("SELECT SUM(amount) AS total_amount FROM loan_list WHERE member_id = " . $user_id . " AND status = " . $status);
$loan_amount = $loan_qry->fetch_assoc();

$loan__qry = $conn->query("SELECT SUM(penalty_accrued) AS total_penalty FROM loan_list WHERE member_id = " . $user_id);
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

<body class="bg-white">
    <?php include 'member-header.php' ?>

    <main class="d-flex flex-column px-12 bg-gradient-to-r from-blue-200 via-blue-300 to-purple-500 ">
        <div class="d-flex flex-row justify-content-between pt-5 gap-5">
            <!-- Profile Card -->
            <div class="bg-purple-200 w-full rounded-xl p-5 d-flex flex-column gap-3 border border-purple-500 shadow">
                <h2 class="font-semibold text-xl">Profile Overview</h2>
                <table class="table table-bordered border-2">
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex flex-row rounded-lg">
                                    <div class="flex place-items-center m-2 p-2 border border-purple-500 bg-white rounded-xl" style="width: 200px; height: 150px;">
                                        <img class="image-fluid" src="<?php echo $baseurl . '/' . $member['profile_pic_path'] ?>" alt="profile pic">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 text-lg bg-white m-2 p-2 border border-gray-500 w-full rounded-xl">
                                        <p class="flex flex-col"><span class="font-bold">Name:</span> <?php echo $member['firstname'] . ' ' . $member['middlename'] . ' ' . $member['lastname'] ?></p>
                                        <p class="flex flex-col"><span class="font-bold">Total Loan Amount:</span> Ksh <?php echo number_format($loan_amount['total_amount'] ? $loan_amount['total_amount'] : 0); ?></p>
                                        <p class="flex flex-col"><span class="font-bold">Total Investments:</span></p>
                                        <p class="flex flex-col"><span class="font-bold">Total Penalty: </span> Ksh <?php echo number_format($loan_penalty['total_penalty'] ? $loan_penalty['total_penalty'] : 0); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- Sacco News Card -->
            <div class="bg-purple-200 w-full rounded-xl p-5 d-flex flex-column justify-content-between gap-3 border border-purple-500 shadow">
                <h2 class="font-semibold text-xl">Upcoming Sacco News</h2>
                <table class="table table-bordered border-2">
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex flex-row">
                                    <div class="flex place-items-center m-2 p-2 rounded-xl bg-white border border-purple-500" style="width: 200px; height: 150px;">
                                        <img class="image-fluid" src="<?php echo $baseurl . '/' . $news['article_image_path'] ?>" alt="article pic">
                                    </div>
                                    <div class="flex items-center gap-3 text-lg rounded-xl m-2 p-2 w-full bg-white border border-purple-500">
                                        <p><span class="font-bold">Article Title:</span> <?php echo $news['article_title'] ? $news['article_title'] : 'N/A' ?></p>
                                        <p><span class="font-bold">Article Content:</span> <?php echo $news['article_content'] ? $news['article_content'] : 'N/A'; ?></p>
                                        <p><span class="font-bold">Date Created:</span> <?php echo $news['date_created'] ? $news['date_created'] : 'N/A'; ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>
                <a class="text-purple-500 text-right" href="member-news.php">View More <i class="fa-solid fa-right-long"></i></a>
            </div>
        </div>
        <div class="row pt-7 h-50">
            <!-- Investments Card -->
            <div class="col-md-4">
                <div class="bg-blue-100 rounded-xl p-5 d-flex flex-column justify-content-between border border-blue-500 shadow">
                    <h2 class="font-semibold text-xl">Investments</h2>
                    <h3 class="text-blue-500 font-bold text-2xl">Ksh 13,000</h3>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a class="text-blue-500 text-right" href="investments.html">View More <i class="fa-solid fa-right-long"></i></a>
                </div>
            </div>
            <!-- Savings Card -->
            <div class="col-md-4">
                <div class="bg-green-100 rounded-xl p-5 d-flex flex-column justify-content-between border border-green-500 shadow">
                    <h2 class="font-semibold text-xl">Savings Account</h2>
                    <h3 class="text-green-500 font-bold text-2xl">Ksh 20,000</h3>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a class="text-green-500 text-right" href="account.html">View More <i class="fa-solid fa-right-long"></i></a>
                </div>
            </div>
            <!-- Loans Card -->
            <div class="col-md-4">
                <div class="bg-red-100 rounded-xl p-5 d-flex flex-column justify-content-between border border-red-500 shadow">
                    <h2 class="font-semibold text-xl">Loans and Repayment</h2>
                    <h3 class="text-red-500 font-bold text-2xl">Ksh <?php echo number_format($loan_amount['total_amount'] ? $loan_amount['total_amount'] : 0); ?></h3>
                    <div class="h-100 w-100 bg-danger rounded-lg"></div>
                    <a class="text-red-500 text-right" href="member-loan.php">View More <i class="fa-solid fa-right-long"></i></a>
                </div>
            </div>
        </div>
    </main>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>