<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "class-section";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>All Class Sections</title>
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
    <?php require_once dirname(__DIR__,2) . '/navbar.php'; ?>
    <main>
        <a href="./teacher/dashboard">
            <button id="back-btn">Back</button>
        </a>
        <h1>All Class Sections</h1>

        <section class="quicklinks">
            <table id="quicklinks">
                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php
                // SQL query to retrieve all class sections with adviser names
                $sql = "SELECT 
                    cs.`class-section_id` AS class_section_id,
                    cs.section_name,
                    cs.school_year,
                    cs.term_no,
                    CONCAT(
                        TRIM(COALESCE(u.user_first_name, '')), ' ',
                        TRIM(COALESCE(u.user_middle_name, '')), ' ',
                        TRIM(COALESCE(u.user_last_name, ''))
                    ) AS adviser_name
                FROM `class-sections` cs
                LEFT JOIN users u
                    ON cs.adviser_id = u.user_id
                WHERE cs.adviser_id = $user_id";

                $result = $data->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $section_id = $row["class_section_id"];
                        $section_name = $row["section_name"];
                        $school_year = $row["school_year"];
                        $term_no = $row["term_no"];
                        $adviser_name = trim($row["adviser_name"]);
                        if (empty($adviser_name)) {
                            $adviser_name = "No Adviser Assigned";
                        }
                        ?>

                        <tr>
                            <td style="text-align:left;">

                                <!-- SECTION NAME -->
                                <div style="font-weight:bold; font-size:16px;">
                                    <?php echo $section_name; ?>
                                </div>

                                <!-- ADVISER NAME (SMALL TEXT) -->
                                <div style="font-size:12px; color:gray; margin-top:3px;">
                                    Adviser: <?php echo $adviser_name; ?>
                                </div>

                                <!-- TERM + SCHOOL YEAR -->
                                <div style="font-size:12px; color:#666;">
                                    Term <?php echo $term_no; ?> | A.Y. <?php echo $school_year; ?>
                                </div>

                            </td>

                            <td>
                                <a href="./teacher/class-section/view/<?php echo $section_id; ?>">
                                    <button id="view-btn">View</button>
                                </a>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    echo "<tr><td colspan='2'>No class sections found</td></tr>";
                }
                ?>
            </table>
    </main>
        <?php require_once dirname(__DIR__,2) . '/footer.php'; ?>

</body>

</html>