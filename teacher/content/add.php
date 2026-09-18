<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "content";
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
    $youtube_embed = trim($_POST['youtube-embed']);
    $audience = $_POST['audience'];

    // Get user_id from session
    $user_id = $_SESSION['user_id'];
    // Get the current date
    $post_date = date("Y-m-d"); // Format: YYYY-MM-DD

    if (empty($youtube_embed)) {
        $_SESSION['error'] = "YouTube embed link is required.";
        header("Location: add");
        exit();
    }

    if (!str_contains($youtube_embed, "youtube.com/embed/")) {
        $_SESSION['error'] = "Invalid YouTube link. Please use EMBED link format (youtube.com/embed/VIDEO_ID).";
        header("Location: add");
        exit();
    }

    // Prepare SQL statement to insert data into announcements table
    $sql = "INSERT INTO `learning-contents` (`learning_content_title`, `learning_content_description`, `learning_content_upload_date`, `learning_content_posted_by`, `learning_content_class_section`, `learning_content_youtube_embed`) 
			VALUES ('$title', '$description', '$post_date', '$user_id', '$audience', '$youtube_embed')";

    // Execute SQL statement
    if (mysqli_query($data, $sql)) {
        // header("location: management");
        $_SESSION['success'] = "Learning content added successfully!";
        header("Location: ../content/management");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add content: " . mysqli_error($data);
        header("Location: add");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Add Learning Content</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
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

        .contents-form {
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
        <a href="./teacher/content/management">
            <button id="back-btn">Back</button>
        </a>

        <section class="content-management">
            <h2>Add New Learning Content</h2>
            <?php if (isset($_SESSION['error'])) { ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
            <form class="contents-form" action="" method="post">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="text" name="youtube-embed" placeholder="Youtube Embed Link" required>
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>
                    <?php echo implode("", $options); // Output section options ?>
                    <!-- ADD OTHER OPTIONS FOR ADDED, EXISTING CLASS SECTIONS -->
                </select>
                <button name="submit" type="submit">Add Learning Content</button>
            </form>
        </section>
    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>

</body>

</html>