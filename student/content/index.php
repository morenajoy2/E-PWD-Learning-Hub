<?php
$required_role = "Student";
require_once dirname(__DIR__, 2) . '/auth.php';

$active = "content";
require_once dirname(__DIR__, 2) . '/db_connection.php';

/* GET USER SECTION */
$user_id = $_SESSION['user_id'];

$userQuery = "SELECT user_class_sections 
              FROM users 
              WHERE user_id = $user_id";

$userResult = $data->query($userQuery);

$userRow = $userResult->fetch_assoc();
$user_section = $userRow['user_class_sections'];

/* FILTER CONTENT BY SECTION */
$sql = "SELECT learning_content_id, learning_content_title 
        FROM `learning-contents`
        WHERE learning_content_class_section = '$user_section'";

$result = $data->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Contents</title>
    <base href="/EPWD/">
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/view-all.css">
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

        #view-btn {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>

        <a href="./student/dashboard">
            <button id="back-btn">Back</button>
        </a>

        <h1>All Learning Contents</h1>

        <table id="quicklinks">

            <?php

            if ($result && $result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    echo "<tr>";
                    echo "<td>" . $row["learning_content_title"] . "</td>";
                    echo "<td>
                <a href='student/content/view/" . $row["learning_content_id"] . "'>
                    <button id='view-btn'>View</button>
                </a>
              </td>";
                    echo "</tr>";
                }

            } else {
                echo "<tr><td colspan='2'>No learning contents found for your section</td></tr>";
            }

            ?>

        </table>

    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>

</body>

</html>