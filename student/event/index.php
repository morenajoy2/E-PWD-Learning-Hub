<?php
$required_role = "Student";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "event";
require_once dirname(__DIR__, 2) . '/db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events</title>
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

        .btn {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <div class="btn">
            <a href="./student/dashboard">
                <button id="back-btn">Back</button>
            </a>

            <!-- may line ung gilid ng button, paki tanggal po, ayaw matanggal -->
            <a href="./student/event/calendar-monthly">
                <button id="back-btn">Monthly</button>
            </a>
            <a href="./student/event/calendar-weekly">
                <button id="back-btn">Weekly</button>
            </a>
        </div>

        <h1>All Events</h1>

        <section class="quicklinks">
            <table id="quicklinks">
                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php
                // Fetch table from event
                $sql = "SELECT * FROM event";
                $result = mysqli_query($data, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_table = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row_table["event_name"]; ?></td>
                                <td>
                                    <a href="./student/event/view?id=<?php echo $row_table["event_id"]; ?>">
                                        <button id="view-btn">View</button>
                                    </a>
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
        </section>
    </main>


    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>