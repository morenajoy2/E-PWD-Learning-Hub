<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';

$active = "class-section";

/* GET SESSION (ADVISER ID) */
$adviser_id = $_SESSION['user_id'] ?? null;

if (!$adviser_id) {
    die("No logged-in teacher found.");
}

/* GET CLASS SECTIONS */
$query = "SELECT `class-section_id`, `section_name` FROM `class-sections`";
$result = mysqli_query($data, $query);

$options = [];
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = "<option value='{$row['class-section_id']}'>{$row['section_name']}</option>";
}

/* GET STUDENTS */
$studentQuery = "SELECT user_id, user_first_name, user_last_name 
                 FROM users 
                 WHERE user_type = 'Student'";

$studentResult = mysqli_query($data, $studentQuery);

/* SUBMIT */
if (isset($_POST["submit"])) {

    $section_name = mysqli_real_escape_string($data, $_POST['section-name']);
    $school_year = mysqli_real_escape_string($data, $_POST['school-year']);
    $term_no = mysqli_real_escape_string($data, $_POST['term-no']);

    $students = $_POST['students'] ?? [];

    /* INSERT CLASS SECTION */
    $insert = "
        INSERT INTO `class-sections`
        (section_name, school_year, term_no, adviser_id)
        VALUES
        ('$section_name', '$school_year', '$term_no', '$adviser_id')
    ";

    if (!mysqli_query($data, $insert)) {
        die("Insert Error: " . mysqli_error($data));
    }

    /* GET NEW SECTION ID */
    $class_section_id = mysqli_insert_id($data);

    /* ASSIGN STUDENTS */
    foreach ($students as $student_id) {

        $student_id = (int) $student_id;

        $update = "
            UPDATE users
            SET user_class_sections = '$class_section_id'
            WHERE user_id = '$student_id'
        ";

        if (mysqli_query($data, $update)) {
            $_SESSION['success'] = "Class section added successfully!";
            header("Location: management");
            exit();
        } else {
            $_SESSION['error'] = "Failed to assign student: " . mysqli_error($data);
            header("Location: add");
            exit();
        }
    }

    // header("Location: management");
    // exit();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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

        .dropdown-multiselect {
            width: 215px;
            position: relative;
            color: #696969;
            font-size: 14px;
        }

        .dropdown-btn {
            padding: 8px 10px;
            border: 1px solid #ccc;
            cursor: pointer;
            background: white;
            border-radius: 4px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            /* ⭐ IMPORTANT FIX */
        }

        .dropdown-text {
            margin: 0;
        }

        .dropdown-btn i {
            font-size: 12px;
            color: #696969;
            margin-left: 8px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background: white;
            z-index: 10;
        }

        .dropdown-content label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            cursor: pointer;
            color: #696969;
        }

        .dropdown-content label:hover {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <a href="./teacher/class-section/management">
            <button id="back-btn">Back</button>
        </a>

        <section class="class-section-management">
            <h2>Add New Class Section</h2>
            <form class="class-section-form" action="" method="post">
                <?php if (isset($_SESSION['error'])) { ?>
                    <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                        <?= $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php } ?>
                <input type="text" name="section-name" placeholder="Section Name" required>
                <input type="text" name="school-year" placeholder="School Year" required>
                <input type="text" name="term-no" placeholder="Term No" required>
                <div class="dropdown-multiselect">
                    <div class="dropdown-btn" onclick="toggleDropdown()">
                        <!-- Select Students        ▼ -->
                        <p>Select Students</p>
                        <i class="bi bi-caret-down-square-fill"></i>
                    </div>

                    <div id="dropdown-content" class="dropdown-content">

                        <?php while ($row = mysqli_fetch_assoc($studentResult)) { ?>
                            <label>
                                <input type="checkbox" name="students[]" value="<?= $row['user_id']; ?>">
                                <?= $row['user_last_name'] . ", " . $row['user_first_name']; ?>
                            </label>
                        <?php } ?>

                    </div>
                </div>
                <button name="submit" type="submit">Add Class Section</button>
            </form>
        </section>


    </main>
    <script>
        function toggleDropdown() {
            var content = document.getElementById("dropdown-content");
            content.style.display = (content.style.display === "block") ? "none" : "block";
        }

        // close dropdown if click outside
        document.addEventListener('click', function (event) {
            var dropdown = document.querySelector('.dropdown-multiselect');
            if (!dropdown.contains(event.target)) {
                document.getElementById("dropdown-content").style.display = "none";
            }
        });
    </script>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>

</body>

</html>