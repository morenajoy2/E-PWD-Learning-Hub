<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "announcement";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';

$id = "";
$title = "";
$description = "";
$class_section = "";

$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET method: show the data

    if (!isset($_GET["id"])) {
        header("location: /EPWD/admin/announcement/management");
        exit;
    }

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        die("Invalid request");
    }

    // Read the row of the selected announcement from the database
    $sql = "SELECT * FROM announcements WHERE announcement_id = $id";
    $result = mysqli_query($data, $sql);
    $row_table = mysqli_fetch_assoc($result);

    if (!$row_table) {
        header("location: /EPWD/admin/announcement/management");
        exit;
    }

    $id = $row_table["announcement_id"];
    $title = $row_table["announcement_title"];
    $description = $row_table["description"];
    $class_section = $row_table["announcement_class_section"];

} else {
    // POST method: update the data

    $id = $_POST["id"];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $class_section = $_POST['audience'];

    if (empty($id) || empty($title) || empty($description) || empty($class_section)) {
        $errorMsg = "All the fields are required";
    } else {
        // Update the announcement data in the database
        $updatedsql = "UPDATE announcements 
                       SET announcement_title = '$title', 
                           description = '$description',
                           announcement_class_section = '$class_section' 
                       WHERE announcement_id = $id";


        $result_update = mysqli_query($data, $updatedsql);

        if (!$result_update) {
            $errorMsg = "Invalid query: " . mysqli_error($data);
        } else {
            $_SESSION['success'] = "Announcement updated successfully!";
            header("location: /EPWD/admin/announcement/management");
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
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/add-announcement.css">
    <link rel="icon" type="image/x-icon" href="images/epwd-favicon.png">

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

        /* ALSO USED FOR EDIT ANNOUNCEMENT */

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
            <h2>Edit Announcement</h2>
            <form class="announcements-form" action="" method="post">
                <!-- POPULATE INPUT FIELDS WITH EXISTING INFORMATION ABOUT THE SELECTED EVENT -->
                <?php
                if (!empty($errorMsg)) {
                    echo "<center><strong>$errorMsg</strong></center>";
                }
                ?>
                <input type="hidden" value="<?php echo $id; ?>" name="id" required>
                <input type="text" name="title" value="<?php echo $title; ?>" required>
                <textarea name="description" required><?php echo $description; ?></textarea>
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>

                    <?php
                    $section_sql = "SELECT * FROM `class-sections` ORDER BY section_name";
                    $section_result = mysqli_query($data, $section_sql);

                    while ($section = mysqli_fetch_assoc($section_result)) {

                        $selected = ($class_section == $section['class-section_id']) ? 'selected' : '';

                        echo "<option value='{$section['class-section_id']}' $selected>
                {$section['section_name']}
              </option>";
                    }
                    ?>
                </select>
                <button type="submit">Save Changes</button>
            </form>
        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>