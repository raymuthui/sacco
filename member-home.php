<?php
session_start();

include('db_connect.php');

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Member Home</title>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            background-color: #fff7df;
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

<body>
    <header class="flex-column sticky">
        <nav class="navbar navbar-light" style="padding:0;">
            <div class="container-fluid mt-2 mb-2">
                <div class="col-lg-12 d-flex align-items-center justify-content-between">
                    <div class="col-md-2 float-left" style="display: flex;">
                        <div class="logo">
                            <img src="assets/img/logo.png" width="320px">
                        </div>
                    </div>

                    <div class="col-md-6 text-right text-dark">
                        <strong><a href="https://www.enchanted-tech.com/" target="a_blank" class="text-dark">Contact Author : Enchanted Tech.</a></strong>
                    </div>
                </div>
            </div>


            <div class="flex flex-row justify-between px-12 py-4 w-full bg-[#f2efef]">
                <div class="flex justify-item-start">
                    <strong><p>Welcome to UTUMISHI Sacco, Member1!</p></strong>
                </div>

                <div class="flex flex-row gap-2">
                    <img class="" width="25px" height="25px" src="./assets/img/gear.png" alt="settings">
                    <strong><p>Settings</p></strong>
                </div>
            </div>
        </nav>
    </header>

    <main class="d-flex flex-column px-12">
        <div class="d-flex flex-row justify-content-between pt-5 gap-5 h-50">
            <div class="bg-white w-50 rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                <h2 class="font-semibold text-xl">Profile Overview</h2>
                <div class="h-100 w-100 bg-danger rounded-lg"></div>
            </div>
            <div class="bg-white w-50 rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                <h2 class="font-semibold text-xl">Upcoming Sacco News</h2>
                <div class="h-100 w-100 bg-danger rounded-lg"></div>
                <a href="news.html">View More</a>
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
                    <a href="loans.html">View More</a>
                </div>
            </div>
        </div>
    </main>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>