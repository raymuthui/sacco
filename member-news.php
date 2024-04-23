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

        /* Read More Styles */
        .article-content {
            overflow: hidden;
            max-height: calc(1.2em * 4); /* Adjust based on your font size */
            line-height: 1.2em; /* Adjust based on your font size */
        }
        .read-more {
            /* display: none; */
            color: blue;
            text-decoration: underline;
        }
        .show-more {
            display: block;
        }
    </style>
</head>

<body class="bg-blue-100">
    <?php include 'member-header.php' ?>

    <main class="d-flex items-center flex-column px-12">
        <h1>Sacco News</h1>
        <!-- Profile Overview section -->
        <!-- Upcoming Sacco News -->
        <div class="container grid grid-cols-3 flex-row justify-content-between p-5 gap-5">
            <?php while ($news = $news_qry->fetch_assoc()) { ?>
                <div class="bg-white w-full rounded-xl p-3 d-flex flex-column justify-content-between shadow border-solid border-gray-200">
                    <div class="d-flex flex-column bg-white rounded-lg">
                        <div class="flex justify-center m-2 p-2 rounded-md bg-[#f2efef]" style="width: 100%; height: 150px;">
                            <img class="w-auto h-full image-fluid" src="<?php echo $baseurl . '/' . $news['article_image_path'] ?>" alt="article pic">
                        </div>
                        <div class="rounded-md bg-white m-2 p-2 w-full">
                            <h2><?php echo $news['article_title'] ? $news['article_title'] : 'N/A' ?></h2>
                            <div class="article-content" style="height: 150px;">
                                <p class="text-ellipsis"><?php echo $news['article_content'] ? $news['article_content'] : 'N/A'; ?></p>
                            </div>
                            <?php if (strlen($news['article_content']) > 4 * 80) { ?>
                                <button class="read-more" data-toggle="modal" data-target="#articleModal" data-article="<?php echo htmlentities($news['article_content']); ?>">Read more</button>
                            <?php } ?>
                            <p><b>Date Created:</b> <?php echo $news['date_created'] ? $news['date_created'] : 'N/A'; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Other sections of your main content -->
    </main>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="articleModal" tabindex="-1" role="dialog" aria-labelledby="articleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="articleModalLabel">Full Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="fullArticleContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.read-more').click(function() {
                var articleContent = $(this).data('article');
                $('#fullArticleContent').html(articleContent);
            });
        });
    </script>
</body>

</html>
