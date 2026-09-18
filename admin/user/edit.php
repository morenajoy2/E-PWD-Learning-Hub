<?php

$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "user";
require_once dirname(__DIR__, 2) . '/db_connection.php';

$id = $_GET['id'];

$firstname = "";
$middlename = "";
$lastname = "";
$email = "";
$password = "";
$role = "";
$class_section = "";

$options = array();

$errorMsg = "";
$successMsg = "";

/* GET USER DATA */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT * FROM users WHERE user_id = '$id'";
    $result = mysqli_query($data, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("User not found");
    }

    $row_table = mysqli_fetch_assoc($result);

    $firstname = $row_table["user_first_name"];
    $middlename = $row_table["user_middle_name"];
    $lastname = $row_table["user_last_name"];
    $email = $row_table["user_email"];
    $password = $row_table["user_password"];
    $role = $row_table["user_type"];
    $class_section = $row_table["user_class_sections"];

    /* BUILD CLASS SECTION OPTIONS */
    $query = "SELECT `class-section_id`, `section_name`
              FROM `class-sections`";

    $sectionResult = mysqli_query($data, $query);

    while ($row = mysqli_fetch_assoc($sectionResult)) {

        $selected = ($row['class-section_id'] == $class_section)
            ? "selected"
            : "";

        $options[] =
            "<option value='{$row['class-section_id']}' $selected>
                {$row['section_name']}
            </option>";
    }
}

/* UPDATE USER */ else {

    $id = $_POST["id"];
    $firstname = $_POST["first-name"];
    $middlename = $_POST["middle-name"];
    $lastname = $_POST["last-name"];
    $email = $_POST["email-address"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $class_section = $_POST["class-section"];

    if (
        empty($firstname) ||
        empty($lastname) ||
        empty($email) ||
        empty($password) ||
        empty($role)
    ) {

        $errorMsg = "All fields are required";

    } else {

        $updatedsql = "
            UPDATE users
            SET
                user_first_name = '$firstname',
                user_middle_name = '$middlename',
                user_last_name = '$lastname',
                user_email = '$email',
                user_password = '$password',
                user_type = '$role',
                user_class_sections = '$class_section'
            WHERE user_id = '$id'
        ";

        $result_update = mysqli_query($data, $updatedsql);

        if (!$result_update) {
            $errorMsg = mysqli_error($data);
        } else {
            $_SESSION['success'] = "User updated successfully!";
            header("Location: /EPWD/admin/user/management");
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

    <title>Edit User</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/add-user.css">
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
        <a href="./admin/user/management">
            <button id="back-btn">Back</button>
        </a>
        <h1></h1>

        <section class="user-management">
            <h2>Edit User</h2>
            <form class="user-form" action="" method="post">
                <?php
                if (!empty($errorMsg)) {
                    echo "<center><strong>$errorMsg</strong></center>";
                }
                ?>
                <input type="hidden" value="<?php echo $id; ?>" name="id" required>
                <input type="text" value="<?php echo $firstname; ?>" name="first-name" required>
                <input type="text" value="<?php echo $middlename; ?>" name="middle-name">
                <input type="text" value="<?php echo $lastname; ?>" name="last-name" required>
                <input type="email" value="<?php echo $email; ?>" name="email-address" required>
                <input type="password" value="<?php echo $password; ?>" name="password" required>
                <select name="role">
                    <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                    <option value="">-- Select User Type --</option>
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                    <option value="Parent">Parent</option>
                </select>
                <select name="class-section">

                    <option value="">-- Select Class Section --</option>

                    <?php echo implode("", $options); ?>

                    <option value="Everyone" <?php echo ($class_section == "Everyone") ? "selected" : ""; ?>>
                        Everyone
                    </option>

                </select>

                <?php
                if (!empty($successMsg)) {
                    echo "<center><strong>$successMsg</strong></center>";
                }
                ?>

                <button type="submit">Save Changes</button>
            </form>
        </section>
    </main>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>

</html>