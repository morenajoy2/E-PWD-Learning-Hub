<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';

$active = "class-section";

$id = "";
$sectionname = "";
$schoolyear = "";
$termno = "";
$adviser = "";

$errorMsg = "";

/* GET CLASS SECTION DATA */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"])) {
        header("location: /EPWD/teacher/class-section/management");
        exit;
    }

    $id = $_GET['id'];

    $sql = "SELECT * FROM `class-sections` WHERE `class-section_id` = $id";
    $result = mysqli_query($data, $sql);
    $row_table = mysqli_fetch_assoc($result);

    if (!$row_table) {
        header("location: /EPWD/teacher/class-section/management");
        exit;
    }

    $sectionname = $row_table["section_name"];
    $schoolyear = $row_table["school_year"];
    $termno = $row_table["term_no"];
    $adviser = $row_table["adviser_id"];

    /* GET TEACHERS */
    $teacherQuery = "SELECT user_id, user_last_name 
                     FROM users 
                     WHERE user_type='Teacher'";
    $teacherResult = mysqli_query($data, $teacherQuery);

    $options = [];
    while ($row = mysqli_fetch_assoc($teacherResult)) {

        $selected = ($row['user_id'] == $adviser) ? "selected" : "";

        $options[] = "<option value='{$row['user_id']}' $selected>
                        {$row['user_last_name']}
                      </option>";
    }

    /* GET ALL STUDENTS */
    $studentQuery = "SELECT user_id, user_first_name, user_last_name 
                     FROM users 
                     WHERE user_type = 'Student'";
    $studentResult = mysqli_query($data, $studentQuery);

    /* GET CURRENT ASSIGNED STUDENTS */
    $assignedQuery = "SELECT user_id FROM users WHERE user_class_sections = $id";
    $assignedResult = mysqli_query($data, $assignedQuery);

    $assignedStudents = [];

    while ($row = mysqli_fetch_assoc($assignedResult)) {
        $assignedStudents[] = (int) $row['user_id'];
    }
}

/* UPDATE */ else {

    $id = $_POST["id"];
    $sectionname = $_POST["section-name"];
    $schoolyear = $_POST["school-year"];
    $termno = $_POST["term-no"];
    $adviser = $_POST["adviser"];
    $students = $_POST['students'] ?? [];

    if (empty($id) || empty($sectionname) || empty($schoolyear)) {
        $errorMsg = "All fields are required";
    } else {

        /* UPDATE CLASS SECTION */
        $sql = "UPDATE `class-sections`
                SET section_name='$sectionname',
                    school_year='$schoolyear',
                    term_no='$termno',
                    adviser_id='$adviser'
                WHERE `class-section_id`=$id";

        mysqli_query($data, $sql);

        /* RESET OLD STUDENTS */
        mysqli_query($data, "UPDATE users SET user_class_sections = NULL WHERE user_class_sections = $id");

        /* ASSIGN NEW STUDENTS */
        foreach ($students as $student_id) {
            mysqli_query(
                $data,
                "UPDATE users 
                 SET user_class_sections = '$id' 
                 WHERE user_id = '$student_id'"
            );
        }
        $_SESSION['success'] = "Class section updated successfully!";
        header("location: /EPWD/teacher/class-section/management");
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

    <title>Edit Class Section</title>
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
            margin-top: 5px;
            width: 215px;
            position: relative;
            color: #000000;
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
        }

        .dropdown-text {
            margin: 0;
        }

        .dropdown-btn i {
            font-size: 12px;
            color: #000000;
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
            color: #000000;
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
            <h2>Edit Class Section</h2>
            <form class="class-section-form" method="post">
                <?php
                if (!empty($errorMsg)) {
                    echo "<div style='color:red; text-align:center; margin-bottom:10px;'>
                <strong>$errorMsg</strong>
              </div>";
                }
                ?>
                <input type="hidden" name="id" value="<?= $id; ?>">

                <input type="text" name="section-name" value="<?= $sectionname; ?>" required>
                <input type="text" name="school-year" value="<?= $schoolyear; ?>" required>
                <input type="text" name="term-no" value="<?= $termno; ?>" required>

                <select name="adviser" required>
                    <option value="">-- Select Adviser --</option>
                    <?php echo implode("", $options); ?>
                </select>

                <!-- DROPDOWN STUDENTS -->
                <div class="dropdown-multiselect">

    <div class="dropdown-btn" onclick="toggleDropdown()">
        <p>Selected Students</p>
        <i class="bi bi-caret-down-square-fill"></i>
    </div>

    <div id="dropdown-content" class="dropdown-content">

        <?php while ($row = mysqli_fetch_assoc($studentResult)) { ?>

            <?php
                $studentId = (int) $row['user_id'];
                $isChecked = in_array($studentId, $assignedStudents);
            ?>

            <label>
                <input type="checkbox"
                       name="students[]"
                       value="<?= $studentId ?>"
                       <?= $isChecked ? 'checked' : '' ?>>

                <?= htmlspecialchars($row['user_last_name'] . ", " . $row['user_first_name']); ?>
            </label>

        <?php } ?>

    </div>
</div>

                <button type="submit">Save Changes</button>

            </form>
        </section>
    </main>
    <script>
        function toggleDropdown() {
            var content = document.getElementById("dropdown-content");
            content.style.display = (content.style.display === "block") ? "none" : "block";
        }

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