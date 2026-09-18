<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "user";

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
    $first_name = $_POST['first-name'];
    $middle_name = $_POST['middle-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['email-address'];
    $password = $_POST['password'];
    $user_type = $_POST['role'];
    $class_section = $_POST['class-section'];

    // to add or insert the values into the users table
    $insertQuery = "INSERT INTO users (user_first_name, user_middle_name, user_last_name, user_email, user_password, user_type, user_class_sections) 
                    VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$password', '$user_type', '$class_section')";

    if (mysqli_query($data, $insertQuery)) {
        $_SESSION['success'] = "User added successfully!";
        header("location: management");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add user: " . mysqli_error($data);
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
    <title>Add User</title>
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

        #class-section-wrapper {
            display: none;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>

    <main>
        <a href="./admin/class-section/management">
            <button id="back-btn">Back</button>
        </a>
        <section class="user-management">
            <h2>Add New User</h2>
            <?php if (isset($_SESSION['error'])) { ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; margin: 10px 0;">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
            <form class="user-form" action="" method="post">
                <input type="text" name="first-name" placeholder="First Name" required>
                <input type="text" name="middle-name" placeholder="Middle Name">
                <input type="text" name="last-name" placeholder="Last Name" required>
                <input type="email" name="email-address" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Default Password" required>
                <select name="role" id="roleSelect" required>
                    <option value="">-- Select User Type --</option>
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                    <option value="Parent">Parent</option>
                </select>
                <div id="class-section-wrapper">
                    <select name="class-section">
                        <option value="">-- Select Student's Class Section --</option>
                        <?php echo implode("", $options); ?>
                    </select>
                </div>
                <button type="submit" name="submit">Add User</button>
            </form>
        </section>
    </main>

    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
    <script>
        document.getElementById("roleSelect").addEventListener("change", function () {

            const role = this.value;
            const wrapper = document.getElementById("class-section-wrapper");

            if (role === "Student" || role === "Teacher") {
                wrapper.style.display = "block";
            } else {
                wrapper.style.display = "none";
            }

        });

        // default state on load
        window.addEventListener("load", function () {
            const role = document.getElementById("roleSelect").value;
            const wrapper = document.getElementById("class-section-wrapper");

            if (role === "Student" || role === "Teacher") {
                wrapper.style.display = "block";
            } else {
                wrapper.style.display = "none";
            }
        });
    </script>

</body>

</html>