<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "announcement";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';

$query = "SELECT `class-section_id`, `section_name` FROM `class-sections`";
$result = mysqli_query($data, $query);

// Check if query was successful
if ($result) {
    // Initialize an array to store section options
    $options = array();

    // Fetch rows from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Add section option to the array
        $options[] = "<option value='{$row['class-section_id']}'>{$row['section_name']}</option>";
    }
    // Free result set
    mysqli_free_result($result);
} else {
    // Query failed
    echo "Error: " . mysqli_error($data);
}

if (isset($_POST["submit"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $class_section = $_POST['audience'];
    // Get user_id from session
    $user_id = $_SESSION['user_id'];
    $post_date = date("Y-m-d"); // Format: YYYY-MM-DD

    // Prepare SQL statement to insert data into announcements table
    $insertQuery = "INSERT INTO `announcements` (`announcement_title`, `description`, `announcement_post-date`, `announcement_posted_by`, `announcement_class_section`) 
			VALUES ('$title', '$description', '$post_date', '$user_id', '$class_section')";

    if (mysqli_query($data, $insertQuery)) {
        $_SESSION['success'] = "Announcement added successfully!";
        header("location: management");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add announcement.";
        echo "Error: " . $insertQuery . "<br>" . mysqli_error($data);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Add Announcement</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/add-announcement.css">
    <link rel="icon" type="image/x-icon" href="./images/epwd-favicon.png">

    <style>
        /* ALSO USED FOR EDIT ANNOUNCEMENT */
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

        h2 {
            margin: 20px 0px;
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            width: 500px;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        select {
            width: 520px;
        }

        .announcements-form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./admin/announcement/management">
            <button id="back-btn">Back</button>
        </a>
        <section class="announcement-management">
            <h2>Add New Announcement</h2>
            <?php if (isset($_SESSION['error'])) { ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
            <form class="announcements-form" action="" method="post">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>
                    <?php echo implode("", $options); // Output section options ?>
                </select>
                <button name="submit" type="submit">Add Announcement</button>
            </form>
        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>
</html>