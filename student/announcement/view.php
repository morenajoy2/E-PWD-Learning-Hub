<?php
$required_role = "Student";

require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';

$active = "announcement";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Title</title> <!-- REPLACE WITH CONTENT TITLE -->
    <base href="/EPWD/">
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/view-individual.css">
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
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./student/announcement/index">
            <button id="back-btn">Back</button>
        </a>
        <?php
        // Include the database connection file
        
        $id = $_GET['announcement_id']; //Change this to a function that somehow is the id of the chosen announcement
        
        // SQL query to retrieve data from the 'announcements' table
        $sql = "SELECT * FROM announcements WHERE announcement_id = $id";

        $result = $data->query($sql);

        if ($result->num_rows == 1) {
            // Fetch the row
            $row = $result->fetch_assoc();

            // Accessing each column's data from the row
            $announcementTitle = $row["announcement_title"];
            $description = $row["description"];
            $postDate = $row["announcement_post-date"];
            $postedBy = $row["announcement_posted_by"];

            // Retrieve the user's name from the 'users' table based on user_id
            $userQuery = "SELECT user_first_name, user_middle_name, user_last_name FROM users WHERE user_id = $postedBy";
            $userResult = $data->query($userQuery);

            if ($userResult->num_rows == 1) {
                $userRow = $userResult->fetch_assoc();
                $postedByName = $userRow["user_last_name"] . ", " . $userRow["user_first_name"] . " " . $userRow["user_middle_name"];
            } else {
                $postedByName = "Unknown User";
            }

            // Output the data within the HTML structure
            echo "<h1>$announcementTitle</h1>";
            echo "<section class='content-description'>";
            echo "<p>Posted By: $postedByName</p>";
            echo "<p>Post Date: $postDate</p>";
            echo "<p>$description</p>";
            echo "</section>";
        } else {
            echo "Announcement not found or multiple announcements found for the provided ID.";
        }
        ?>
    </main>


    <?php require_once dirname(__DIR__, 2) . '\footer.php'; ?>


</body>

</html>