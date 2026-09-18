<?php
$required_role = "Parent";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "announcement";
require_once dirname(__DIR__, 2) . '/db_connection.php';

// SQL query to retrieve all announcement titles
$sql = "SELECT announcement_id, announcement_title FROM announcements";

$result = $data->query($sql);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Announcements</title>
    <base href="/EPWD/">

    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/view-all.css">
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

        #view-btn {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>

    <main>
        <a href="./parent/dashboard">
            <button id="back-btn">Back</button>
        </a>
        <h1>All Announcements</h1>

        <section class="quicklinks">
            <table id="quicklinks">

                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["announcement_title"] . "</td>";
                        echo "<td><a href='./parent/announcement/view/" . $row["announcement_id"] . "'>
                        <button id='view-btn'>View</button>
                      </a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No announcements found</td></tr>";
                }
                ?>


            </table>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>