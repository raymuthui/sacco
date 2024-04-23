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
            display: none;
            color: blue;
            text-decoration: underline;
        }
        .show-more {
            display: block;
        }
    </style>
</head>

<body>
  <?php include 'member-header.php' ?>

    <main class="d-flex items-center flex-column px-12">
        <h1>Sacco News</h1>
        <!-- Profile Overview section -->
        <!-- Upcoming Sacco News -->
        <div class="container grid grid-cols-3 flex-row justify-content-between pt-5 gap-5">
            <?php while ($news = $news_qry->fetch_assoc()) { ?>
                <div style="height: 300px;" class="bg-white w-full rounded-xl p-3 d-flex flex-column justify-content-between shadow">
                    <div class="d-flex flex-row bg-[#f2efef] rounded-lg">
                        <div class="flex place-items-center m-2 p-2 rounded-md bg-white" style="width: 200px; height: 150px; border: 1px solid blue;">
                            <img class="image-fluid" src="<?php echo $baseurl . '/' . $news['article_image_path'] ?>" alt="article pic">
                        </div>
                        <div class="rounded-md bg-white m-2 p-2 w-full">
                            <h2><?php echo $news['article_title'] ? $news['article_title'] : 'N/A' ?></h2>
                            <div class="article-content">
                                <p><?php echo $news['article_content'] ? $news['article_content'] : 'N/A'; ?></p>
                            </div>
                            <?php if (strlen($news['article_content']) > 4 * 80) { ?>
                                <button class="read-more">Read more</button>
                            <?php } ?>
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
    <script>
        const articleContents = document.querySelectorAll('.article-content');
        articleContents.forEach(content => {
            const readMoreButton = content.nextElementSibling;
            const maxLines = 4;
            const hideButton = document.getElementById('hide-button');

            const lineHeight = parseInt(window.getComputedStyle(content).lineHeight);
            const maxHeight = lineHeight * maxLines;

            if (content.scrollHeight > maxHeight) {
                readMoreButton.classList.add('show-more');
            }

            readMoreButton.addEventListener('click', function() {
                content.style.maxHeight = 'none';
                readMoreButton.style.display = 'none';
                // hideButton.style.display = 'block';
            });
        });
    </script>
</body>

</html>
