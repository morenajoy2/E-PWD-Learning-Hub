<?php

$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "class-section";
require_once dirname(__DIR__, 2) . '/db_connection.php';

$id = "";
$sectionname = "";
$schoolyear = "";
$adviser = "";

$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET method: show the data

    if (!isset($_GET["id"])) {
        header("location: /EPWD/admin/event/management");
        exit;
    }

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        die("Invalid request");
    }

    // Read the row of the selected announcement from the database
    $sql = "SELECT * FROM `class-sections` WHERE `class-section_id` = $id";
    $result = mysqli_query($data, $sql);
    $row_table = mysqli_fetch_assoc($result);

    if (!$row_table) {
        header("location: /EPWD/admin/class-section/management");
        exit;
    }

    $adviser = $row_table["adviser_id"];
    $query = "SELECT `user_id`, `user_last_name` FROM `users` WHERE `user_type`='Teacher'";
    $result = mysqli_query($data, $query);
    // Initialize an array to store adviser options
    $options = array();

    while ($row = mysqli_fetch_assoc($result)) {

        $selected = ($row['user_id'] == $adviser) ? "selected" : "";

        $options[] = "<option value='{$row['user_id']}' $selected>
                    {$row['user_last_name']}
                  </option>";
    }
    // Free result set
    mysqli_free_result($result);
    $sectionname = $row_table["section_name"];
    $schoolyear = $row_table["school_year"];
    $termno = $row_table["term_no"];
    $adviser = $row_table["adviser_id"];

} else {
    // POST method: update the data

    $id = $_POST["id"];
    $sectionname = $_POST["section-name"];
    $schoolyear = $_POST["school-year"];
    $termno = $_POST["term-no"];
    $adviser = $_POST["adviser"];

    // Validate and sanitize input data

    if (empty($id) || empty($sectionname) || empty($schoolyear) || empty($adviser)) {
        $errorMsg = "All the fields are required";
    } else {
        // Update the announcement data in the database
        $updatedsql = "UPDATE `class-sections`
                        SET section_name = '$sectionname', 
                            school_year = '$schoolyear',
                            term_no = '$termno',
                            adviser_id = '$adviser'
                        WHERE `class-section_id` = $id";

        $result_update = mysqli_query($data, $updatedsql);

        if (!$result_update) {
            $errorMsg = "Invalid query: " . mysqli_error($data);
        } else {
            $_SESSION['success'] = "Class section updated successfully!";
            header("location: /EPWD/admin/class-section/management");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>Edit Class Section</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/add-class-section.css">
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
        <a href="./admin/class-section/management">
            <button id="back-btn">Back</button>
        </a>

        <section class="class-section-management">
            <h2>Edit Class Section</h2>
            <form class="class-section-form" action="" method="post">
                <?php
                if (!empty($errorMsg)) {
                    echo "<div style='color:red; text-align:center; margin-bottom:10px;'>
                <strong>$errorMsg</strong>
              </div>";
                }
                ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <!-- POPULATE INPUT FIELDS WITH EXISTING INFORMATION ABOUT THE SELECTED EVENT -->
                <input type="text" name="section-name" placeholder="Section Name" required
                    value="<?php echo $sectionname; ?>">
                <input type="text" name="school-year" placeholder="School Year" required
                    value="<?php echo $schoolyear; ?>">
                <input type="text" name="term-no" placeholder="Term No" required value="<?php echo $termno; ?>">
                <select name="adviser" required>
                    <option value="">-- Select Adviser --</option>
                    <!-- ADD OTHER OPTIONS FOR ADDED, EXISTING USERS (USER_TYPE === TEACHER) -->
                    <?php echo implode("", $options); // Output class section options ?>
                </select>
                <button name="submit" type="submit">Save Changes</button>
            </form>
        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>