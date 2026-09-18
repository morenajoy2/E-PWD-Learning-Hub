<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "event";
require_once dirname(__DIR__, 2) . '/db_connection.php';

/* GET FROM CLEAN URL */
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($event_id <= 0) {
    die("Invalid request");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>Event Title</title> <!-- REPLACE WITH CONTENT TITLE -->
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
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./teacher/event/index">
            <button id="back-btn">Back</button>
        </a>

        <?php
        //get id by search
        // $event_id = $_GET['event_id'];

        //fetch all table from event
        $sql = "SELECT * FROM event WHERE event_id='$event_id'";
        $result = mysqli_query($data, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row_table = mysqli_fetch_assoc($result)) {

                    // Retrieve the user's name from the 'users' table based on user_id
                    $var01 = $row_table['event_posted_by'];
                    $userQuery = "SELECT user_first_name, user_middle_name, user_last_name FROM users WHERE user_id = $var01";
                    $userResult = $data->query($userQuery);

                    if ($userResult->num_rows == 1) {
                        $userRow = $userResult->fetch_assoc();
                        $postedByName = $userRow["user_last_name"] . ", " . $userRow["user_first_name"] . " " . $userRow["user_middle_name"];
                    } else {
                        $postedByName = "Unknown User";
                    }

                    ?>

                    <h1> <?php echo $row_table["event_name"]; ?> </h1>
                    <section class="content-description">
                        <p> <?php echo 'Posted By: ' . $postedByName ?></p>
                        <p> <?php echo 'Event Date: ' . $row_table["event_date"] ?></p>
                        <p> <?php echo $row_table["event_description"] ?></p>
                    </section>

                    <?php

                }
            } else {
                echo 'No Event';
            }
            mysqli_free_result($result);
        } else {
            echo 'Error: ' . mysqli_error($data);
        }

        mysqli_close($data);
        ?>
    </main>


        <?php require_once dirname(__DIR__,2) . '/footer.php'; ?>


</body>

</html>