<?php

$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "event";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';

$id = "";
$title = "";
$description = "";
$date = "";
$audience = "";

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

    // Read the row of the selected user from the database
    $sql = "SELECT * FROM event WHERE event_id = $id";
    $result = mysqli_query($data, $sql);
    $row_table = mysqli_fetch_assoc($result);

    if (!$row_table) {
        header("location: /EPWD/admin/event/management");
        exit;
    }
    $title = $row_table["event_name"];
    $description = $row_table["event_description"];
    $date = $row_table["event_date"];
    $audience = $row_table["event_audience"];

    $query = "SELECT `class-section_id`, `section_name` FROM `class-sections`";
    $result = mysqli_query($data, $query);
    // Initialize an array to store section options
    $options = array();

    // Fetch rows from the result set
    while ($row = mysqli_fetch_assoc($result)) {

        $selected = "";

        if ($row['class-section_id'] == $audience) {
            $selected = "selected";
        }

        $options[] = "<option value='{$row['class-section_id']}' $selected>
                    {$row['section_name']}
                  </option>";
    }
    // Free result set
    mysqli_free_result($result);



} else {
    // POST method: update the data

    $id = $_POST["id"];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['event-date'];
    $audience = $_POST['audience'];

    if (empty($id) || empty($title) || empty($description) || empty($date) || empty($audience)) {
        $errorMsg = "All the fields are required";
    } else {
        // Update the user data in the database
        $updatedsql = "UPDATE event 
                SET event_name = '$title', 
                    event_description = '$description',
                    event_date = '$date',
                    event_audience = '$audience' 
                WHERE event_id = $id";


        $result_update = mysqli_query($data, $updatedsql);

        if (!$result_update) {
            $errorMsg = "Invalid query: " . mysqli_error($data);
        } else {
            $_SESSION['success'] = "Event updated successfully!";
            header("location: /EPWD/admin/event/management");
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

    <title>Edit Event</title>
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
        <a href="./admin/event/management">
            <button id="back-btn">Back</button>
        </a>

        <section class="event-management">
            <h2>Edit Event</h2>
            <form class="event-form" action="" method="post">
                <?php
                if (!empty($errorMsg)) {
                    echo "<div style='color:red; text-align:center; margin-bottom:10px;'>
                <strong>$errorMsg</strong>
              </div>";
                }
                ?>
                <input type="hidden" value="<?php echo $id; ?>" name="id" required>
                <input type="text" name="title" value="<?php echo $title; ?>" required>
                <textarea name="description" required><?php echo $description; ?></textarea>
                <input type="date" name="event-date" value="<?php echo $date; ?>">
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>
                    <?php echo implode("", $options); // Output class section options ?>
                    <!-- ADD OTHER OPTIONS FOR ADDED, EXISTING CLASS SECTIONS -->
                </select>
                <button type="submit">Publish Changes</button>
            </form>
        </section>
        <?php
        if (!empty($successMsg)) {
            echo "<center><strong>$successMsg</strong></center>";
        }
        ?>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>