<?php
session_start();
include('db_connect.php');
$baseurl = "http://localhost/sacco";

if (!isset($_SESSION['login_id'])) {
    exit('User is not logged in.');
}

$user_id = $_SESSION['login_id'];
$qry = $conn->query("SELECT * FROM members WHERE id = " . $user_id);
$member = $qry->fetch_assoc();

if (!$member) {
    exit('Member details not found.');
}

$news_qry = $conn->query("SELECT * FROM news ORDER BY date_created DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/872ee97990.js" crossorigin="anonymous"></script>
    <title>Sacco News</title>
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
        main h1 {
            font-weight: bolder;
            font-size: xx-large;
            text-align: center;
            margin-top: 10px;
        }
        main h2 {
            font-weight: bold;
            font-size: x-large;
        }
    </style>
</head>

<body>
  <?php include 'member-header.php' ?>

    <main class="d-flex flex-column px-12">
        <h1>Sacco News</h1>
        <!-- Profile Overview section -->
        <!-- Upcoming Sacco News -->
        <div class="d-flex flex-row justify-content-between pt-5 gap-5">
            <?php while ($news = $news_qry->fetch_assoc()) { ?>
                <div class="bg-white w-50 rounded-xl p-5 d-flex flex-column justify-content-between shadow">
                    <div class="d-flex flex-row bg-[#f2efef] rounded-lg">
                        <div class="flex place-items-center m-2 p-2 rounded-md bg-white" style="width: 200px; height: 150px; border: 1px solid blue;">
                            <img class="image-fluid" src="<?php echo $baseurl . '/' . $news['article_image_path'] ?>" alt="article pic">
                        </div>
                        <div class="rounded-md bg-white m-2 p-2 w-full">
                            <h2><?php echo $news['article_title'] ? $news['article_title'] : 'N/A' ?></h2>
                            <p><?php echo $news['article_content'] ? $news['article_content'] : 'N/A'; ?></p>
                            <p><b>Date Created:</b> <?php echo $news['date_created'] ? $news['date_created'] : 'N/A'; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Other sections of your main content -->
    </main>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
