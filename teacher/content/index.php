<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "content";
require_once dirname(__DIR__, 2) . '/db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>All Contents</title>
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
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./teacher/dashboard">
            <button id="back-btn">Back</button>
        </a>
        <h1>All Learning Contents</h1>

        <section class="quicklinks">
            <table id="quicklinks">
                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php
                // Fetch table from event
                $sql = "SELECT * FROM `learning-contents`
        WHERE learning_content_posted_by = '$user_id'";
                $result = mysqli_query($data, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_table = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row_table["learning_content_title"]; ?></td>

                                <td><a href="teacher/content/view/<?php echo $row_table["learning_content_id"]; ?>">
                                        <button id="view-btn">View</button></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="2"><?php echo 'No Event'; ?></td>
                        </tr>
                    <?php }
                    mysqli_free_result($result);
                } else {
                    echo 'Error: ' . mysqli_error($data);
                }
                mysqli_close($data);
                ?>
            </table>
    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>