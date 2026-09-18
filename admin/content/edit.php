<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "content";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';
$id = "";
$title = "";
$description = "";
$youtube_embed = "";
$audience = "";
$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET method: show the data

    if (!isset($_GET["id"])) {
        header("location: /EPWD/admin/content/management");
        exit;
    }

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        die("Invalid request");
    }

    // Read the row of the selected learning contents from the database
    $sql = "SELECT * FROM `learning-contents` WHERE learning_content_id = $id";
    $result = mysqli_query($data, $sql);
    $row_table = mysqli_fetch_assoc($result);

    if (!$row_table) {
        header("location: /EPWD/admin/content/management");
        exit;
    }
    $audience = $row_table["learning_content_class_section"];
    $title = $row_table["learning_content_title"];
    $description = $row_table["learning_content_description"];
    $youtube_embed = $row_table["learning_content_youtube_embed"];
    $audience = $row_table["learning_content_class_section"];

} else {
    // POST method: update the data
    $id = $_POST["id"];
    $title = mysqli_real_escape_string($data, $_POST["title"]);
    $description = mysqli_real_escape_string($data, $_POST["description"]);
    $youtube_embed = trim($_POST["youtube-embed"]);
    $audience = mysqli_real_escape_string($data, $_POST["audience"]);

    if (empty($id) || empty($title) || empty($description) || empty($youtube_embed) || empty($audience)) {
        $errorMsg = "All the fields are required";

    } else {

        /* =========================
           YOUTUBE VALIDATION
        ========================= */
        if (!str_contains($youtube_embed, "youtube.com/embed/")) {
            $errorMsg = "Invalid YouTube embed link. Please use: youtube.com/embed/VIDEO_ID";

        } else {

            $updatedsql = "UPDATE `learning-contents`
            SET 
                learning_content_title = '$title',
                learning_content_description = '$description',
                learning_content_youtube_embed = '$youtube_embed',
                learning_content_class_section = '$audience'
            WHERE learning_content_id = $id";

            $result_update = mysqli_query($data, $updatedsql);

            if (!$result_update) {
                $errorMsg = "Invalid query: " . mysqli_error($data);
            } else {
                $_SESSION['success'] = "Learning content updated successfully!";
                header("Location: /EPWD/admin/content/management");
                exit();
            }
        }
    }
}

$query = "SELECT `class-section_id`, `section_name` FROM `class-sections`";
$result = mysqli_query($data, $query);
$options = array();

while ($row = mysqli_fetch_assoc($result)) {
    $selected = "";
    if ($row['class-section_id'] == $audience) {
        $selected = "selected";
    }

    $options[] = "<option value='{$row['class-section_id']}' $selected>{$row['section_name']}</option>";
}
mysqli_free_result($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Edit Learning Content</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/add-content.css">
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

        .contents-form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .contents-form button {
            margin-top: 10px;
            display: block;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./admin/content/management">
            <button id="back-btn">Back</button>
        </a>
        <section class="content-management">
            <h2>Edit Learning Content</h2>
            <form class="contents-form" action="" method="post">
                <?php
                if (!empty($errorMsg)) {
                    echo "<div style='color:red; text-align:center; margin-bottom:10px;'>
                <strong>$errorMsg</strong>
              </div>";
                }
                ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="text" name="title" placeholder="Title" required value="<?php echo $title; ?>">
                <textarea name="description" placeholder="Description" required><?php echo $description; ?></textarea>
                <input type="text" name="youtube-embed" placeholder="Youtube Embed Link" required
                    value="<?php echo $youtube_embed; ?>">
                <select name="audience" required>
                    <option value="">-- Select Audience --</option>
                    <?php echo implode("", $options); ?>
                </select>
                <button name="submit" type="submit">Save Changes</button>
            </form>
        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>