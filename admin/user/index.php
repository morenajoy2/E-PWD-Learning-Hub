<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "user";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>All Users</title>
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
        <a href="./admin/dashboard">
            <button id="back-btn">Back</button>
        </a>
        <h1>All Users</h1>

        <section class="quicklinks">
            <table id="quicklinks">
                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php
                $sql = "SELECT * FROM users";
                $result = mysqli_query($data, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

                        $fullName = $row["user_first_name"] . " " .
                            $row["user_middle_name"] . " " .
                            $row["user_last_name"];
                        ?>
                        <tr>
                            <td>
                                <?php echo $row["user_type"] . " - " . $fullName; ?>
                            </td>

                            <td>
                                <a href="./admin/user/view/<?php echo $row["user_id"]; ?>">
                                    <button id="view-btn">View</button>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="2">No Users</td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </section>
    </main>


    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>