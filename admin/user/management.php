<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "user";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>User Management</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/user-mgmt.css">
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

        .btn {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        #back-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>

    <main>
        <h1>User Management</h1>
        <div class="btn">
            <a href="./admin/dashboard">
                <button id="back-btn">Back</button>
            </a>
            <a href="./admin/user/add">
                <button id="add-user-btn">Add User</button>
            </a>
        </div>
        <section class="user-management">
            <?php if (isset($_SESSION['success'])) { ?>
                <div style="color:green; background:#e6ffe6; padding:10px; margin-bottom:10px;">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php } ?>
            <table id="users">
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Password</th>
                    <th>User Type</th>
                    <th>Class Section</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <!-- APPLY FOREACH USER, SHOW ROW OF DETAILS -->
                <?php
                // Fetch table from users
                $sql = "SELECT users.*, `class-sections`.section_name
                    FROM users
                    LEFT JOIN `class-sections`
                    ON users.user_class_sections = `class-sections`.`class-section_id`";
                $result = mysqli_query($data, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_table = mysqli_fetch_assoc($result)) {

                            $id = $row_table["user_id"];
                            $firstname = $row_table["user_first_name"];
                            $middlename = $row_table["user_middle_name"];
                            $lastname = $row_table["user_last_name"];
                            $email = $row_table["user_email"];
                            $password = $row_table["user_password"];
                            $role = $row_table["user_type"];
                            $class_section = !empty($row_table["section_name"])
                                ? $row_table["section_name"]
                                : $row_table["user_class_sections"];
                            ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $firstname; ?></td>
                                <td><?php echo $middlename; ?></td>
                                <td><?php echo $lastname; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $password; ?></td>
                                <td><?php echo $role; ?></td>
                                <td><?php echo $class_section; ?></td>
                                <td><a href="./admin/user/edit/<?php echo $id; ?>"><button id="edit-btn">Edit</button></a></td>
                                <td><button id="delete-btn" onclick="confirmDelete(<?php echo $id; ?>)">Delete</button></td>
                            </tr>
                            <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="11">No Users</td>
                        </tr>
                    <?php }
                    mysqli_free_result($result);
                } else {
                    echo 'Error: ' . mysqli_error($data);
                }
                mysqli_close($data);
                ?>
            </table>
    </main>

    <script>
        function confirmDelete(userId) {
            var confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                window.location.href = "./admin/user/delete/" + userId;
            }
        }
    </script>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>