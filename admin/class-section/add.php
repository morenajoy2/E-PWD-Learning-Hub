<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';

$active = "class-section";

$query = "SELECT `user_id`, `user_last_name` FROM `users` WHERE `user_type` ='Teacher' ";
$result = mysqli_query($data, $query);

// Check if query was successful

if ($result) {
    // Initialize an array to store section options
    $options = array();

    // Fetch rows from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Add section option to the array
        $options[] = "<option value='{$row['user_id']}'>{$row['user_last_name']}</option>";
    }
    // Free result set
    mysqli_free_result($result);
} else {
    // Query failed
    echo "Error: " . mysqli_error($data);
}

if (isset($_POST["submit"])) {
    $sectionname = $_POST['section-name'];
    $schoolyear = $_POST['school-year'];
    $termno = $_POST['term-no'];
    $adviser = $_POST['adviser'];

    // Prepare SQL statement to insert data into announcements table
    $sql = "INSERT INTO `class-sections` (`section_name`, `school_year`, `term_no`, `adviser_id`) 
			VALUES ('$sectionname', '$schoolyear', '$termno', '$adviser')";

    // Execute SQL statement
    if (mysqli_query($data, $sql)) {
        $_SESSION['success'] = "Class section added successfully!";
        header("location: management");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add class section.";
        header("Location: add");
        exit();
        // echo "Error: " . $sql . "<br>" . mysqli_error($data);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Add Class Section</title>
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
            <h2>Add New Class Section</h2>
            <?php if (isset($_SESSION['error'])) { ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
            <form class="class-section-form" action="" method="post">
                <input type="text" name="section-name" placeholder="Section Name" required>
                <input type="text" name="school-year" placeholder="School Year" required>
                <input type="text" name="term-no" placeholder="Term No" required>
                <select name="adviser" required>
                    <option value="">-- Select Adviser --</option>
                    <?php echo implode("", $options); // Output section options ?>
                </select>
                <button name="submit" type="submit">Add Class Section</button>
            </form>
        </section>
    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>

</body>

</html>