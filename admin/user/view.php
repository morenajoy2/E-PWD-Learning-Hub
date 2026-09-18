<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "user";


/* GET FROM CLEAN URL */
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id <= 0) {
    die("Invalid request");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>User Information</title>

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
        <a href="./admin/user/index">
            <button id="back-btn">Back</button>
        </a>

        <h1>User Information</h1>

        <section class="content-description">

            <?php

            /* FETCH USER DATA */
            $sql = "SELECT * FROM users WHERE user_id = '$id'";
            $result = mysqli_query($data, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                ?>

                <p>First Name: <?php echo $row["user_first_name"]; ?></p>
                <p>Middle Name: <?php echo $row["user_middle_name"]; ?></p>
                <p>Last Name: <?php echo $row["user_last_name"]; ?></p>
                <p>Email: <?php echo $row["user_email"]; ?></p>
                <p>User Type: <?php echo $row["user_type"]; ?></p>

                <p>
                    Class Section:
                    <?php
                    // OPTIONAL: show section name instead of ID
                    $sec = $row["user_class_sections"];
                    $q = "SELECT section_name FROM `class-sections` WHERE `class-section_id` = '$sec'";
                    $res = mysqli_query($data, $q);
                    $secName = mysqli_fetch_assoc($res);
                    echo $secName ? $secName["section_name"] : "None";
                    ?>
                </p>

                <?php
            } else {
                echo "User not found";
            }
            ?>

        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>

</body>

</html>