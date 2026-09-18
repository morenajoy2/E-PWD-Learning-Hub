<?php
$required_role = "Admin";

require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';

$active = "content";

/* GET ID FROM URL */
$content_id = isset($_GET['content_id']) ? (int) $_GET['content_id'] : 0;

if ($content_id <= 0) {
    die("Invalid content ID");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>View Content</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/content-mgmt.css">
    <link rel="icon" type="image/x-icon" href="./images/epwd-favicon.png">
    <style>
        main {
            margin-bottom: 140px;
            padding-bottom: 20px;
        }

        footer.footer {
            background-color: #000000;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
            font-size: 14px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        footer.footer a {
            color: #fff;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        footer.footer a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        footer.footer .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        footer.footer .footer-content {
            margin-bottom: 10px;
        }

        footer.footer .footer-links {
            font-size: 13px;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__,2) . '/navbar.php'; ?>

    <main>

        <a href="admin/content/index">
            <button id="back-btn">Back</button>
        </a>

        <?php

        /* FETCH CONTENT (SAFE) */
        $stmt = $data->prepare("SELECT * FROM `learning-contents` WHERE learning_content_id = ?");
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                /* GET USER NAME */
                $posted_by_id = $row['learning_content_posted_by'];

                $userStmt = $data->prepare("
                    SELECT user_first_name, user_middle_name, user_last_name 
                    FROM users 
                    WHERE user_id = ?
                ");
                $userStmt->bind_param("i", $posted_by_id);
                $userStmt->execute();
                $userResult = $userStmt->get_result();

                if ($userResult->num_rows == 1) {
                    $u = $userResult->fetch_assoc();
                    $posted_by = $u["user_last_name"] . ", " .
                        $u["user_first_name"] . " " .
                        $u["user_middle_name"];
                } else {
                    $posted_by = "Unknown User";
                }

                ?>

                <h1><?php echo $row["learning_content_title"]; ?></h1>

                <section class="content-description">

                    <p><strong>Posted By:</strong> <?php echo $posted_by; ?></p>
                    <p><strong>Date:</strong> <?php echo $row["learning_content_upload_date"]; ?></p>

                    <p><?php echo $row["learning_content_description"]; ?></p>

                    <!-- YOUTUBE EMBED -->
                    <iframe width="560" height="315" src="<?php echo $row["learning_content_youtube_embed"]; ?>" frameborder="0"
                        allowfullscreen>
                    </iframe>

                </section>

                <?php
            }
        } else {
            echo "<p>No Content Found</p>";
        }
        ?>

    </main>
    <?php require_once dirname(__DIR__,2) . '\footer.php'; ?>
</body>

</html>