<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "event";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';


$query = "SELECT `class-section_id`, `section_name` FROM `class-sections`";
$result = mysqli_query($data, $query);

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
    $date = date("Y-m-d");
    $audience = $_POST['audience'];
    $user_id = $_SESSION["user_id"];

    $insertQuery = "INSERT INTO event (event_name, event_date, event_description, event_audience, event_posted_by) 
                VALUES ('$title', '$date', '$description', '$audience', '$user_id')";


    if (mysqli_query($data, $insertQuery)) {
        $_SESSION['success'] = "Event added successfully!";
        header("location: management");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add event: " . mysqli_error($data);
        header("Location: add");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="/EPWD/">

<title>Add Event</title>
<link rel="stylesheet" href="./styles/navbar.css">
<link rel="stylesheet" href="./styles/styles.css">
<link rel="stylesheet" href="./styles/add-event.css">
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
        <a href="./teacher/event/management">
            <button id="back-btn">Back</button>
        </a>

        <section class="event-management">
            <h2>Add New Event</h2>
            <form class="event-form" action="" method="post">
                <?php if (isset($_SESSION['error'])) { ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="date" name="event-date" placeholder="Event Date" required>
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>
                    <?php echo implode("", $options); // Output section options ?>
                    <!-- ADD OTHER OPTIONS FOR ADDED, EXISTING CLASS SECTIONS -->
                </select>
                <button type="submit" name="submit">Publish Event</button>
            </form>
        </section>
    </main>
        <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>