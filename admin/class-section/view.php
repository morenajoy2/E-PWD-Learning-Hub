<?php
// Database connectivity
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "class-section";
    
/* GET FROM CLEAN URL */
$class_section_id = isset($_GET['class_section_id']) ? $_GET['class_section_id'] : null;

if ($class_section_id <= 0) {
    die("Invalid request");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>Class Section</title>
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

        #contents {
            border-collapse: collapse;
            width: 100%;
            margin: 16px 0;
        }

        #contents td,
        #contents th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        #contents tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #contents tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./admin/class-section/index">
            <button id="back-btn">Back</button>
        </a>

        <?php
        // Fetch class section details from the database using $class_section_id and join adviser name
        $sql = "SELECT cs.*, 
                       CONCAT(TRIM(COALESCE(u.user_last_name, '')), ', ', TRIM(COALESCE(u.user_first_name, '')), ' ', TRIM(COALESCE(u.user_middle_name, ''))) AS adviser_name
                FROM `class-sections` cs
                LEFT JOIN `users` u ON cs.adviser_id = u.user_id
                WHERE cs.`class-section_id` = '$class_section_id'";

        $result = mysqli_query($data, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            $row_table = mysqli_fetch_assoc($result);

            $adviserName = trim($row_table['adviser_name']);
            if (empty($adviserName)) {
                $adviserName = "No Adviser Assigned";
            }
            ?>
            <h1><?php echo $row_table["section_name"]; ?></h1>

            <section class="content-description">
                <p>Adviser: <?php echo $adviserName; ?></p>


                <table id="contents">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                    </tr>

                    <?php
                    $studentsQuery = "SELECT user_first_name, user_middle_name, user_last_name
                          FROM users
                          WHERE user_class_sections = $class_section_id
                          AND user_type = 'student'";

                    $studentsResult = $data->query($studentsQuery);

                    if ($studentsResult && $studentsResult->num_rows > 0) {

                        $count = 1;

                        while ($s = $studentsResult->fetch_assoc()) {

                            $fullName = $s["user_last_name"] . ", " .
                                $s["user_first_name"] . " " .
                                $s["user_middle_name"];

                            echo "<tr>";
                            echo "<td>$count</td>";
                            echo "<td>$fullName</td>";
                            echo "</tr>";

                            $count++;
                        }

                    } else {
                        echo "<tr><td colspan='2'>No students enrolled</td></tr>";
                    }
                    ?>
                </table>
            </section>
            <?php
        } else {
            echo "<p>Class section not found.</p>";
        }
        ?>
    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>