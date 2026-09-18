<?php
$required_role = "Admin";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "announcement";
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Announcement Management</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/styles.css">
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

        .btn {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        #announcements {
            border-collapse: collapse;
            width: 100%;
            margin: 16px 0px;
        }

        #announcements td {
            max-width: 200px;
            word-wrap: break-word;
        }

        #announcements td,
        #announcements th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        #announcements tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #announcements tr:hover {
            background-color: #ddd;
        }

        #edit-btn {
            background-color: blue;
        }

        #delete-btn {
            background-color: red;
        }

        #add-announcement-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>
    <main>
        <h1>Announcement Management</h1>

        <div class="btn">
            <a href="./admin/dashboard">
                <button id="back-btn">Back</button>
            </a>
            <a href="./admin/announcement/add">
                <button id="add-anouncement-btn">Add Announcement</button>
            </a>
        </div>

        <section class="announcement-management">
            <?php if (isset($_SESSION['success'])) { ?>
                <div style="color:green; background:#e6ffe6; padding:10px; margin-bottom:10px;">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php } ?>
            <table id="announcements">
                <tr>
                    <th>Announcement ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Post Date</th>
                    <th>Posted By</th>
                    <th>Audience</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <!-- APPLY FOREACH ANNOUNCEMENT, SHOW ROW OF DETAILS -->
                <?php
                // Fetch table from users
                $sql = "SELECT * FROM announcements";
                $result = mysqli_query($data, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_table = mysqli_fetch_assoc($result)) {

                            // Retrieve the user's name from the 'users' table based on user_id
                            $var01 = $row_table['announcement_posted_by'];
                            $userQuery = "SELECT user_first_name, user_middle_name, user_last_name FROM users WHERE user_id = $var01";
                            $userResult = $data->query($userQuery);

                            if ($userResult->num_rows == 1) {
                                $userRow = $userResult->fetch_assoc();
                                $posted_by = $userRow["user_last_name"] . ", " . $userRow["user_first_name"] . " " . $userRow["user_middle_name"];
                            } else {
                                $posted_by = "Unknown User";
                            }

                            // Retrieve the user's name from the 'users' table based on user_id
                            $var02 = $row_table['announcement_class_section'];
                            $sectionQuery = "SELECT section_name FROM `class-sections` WHERE `class-section_id` = $var02";
                            $sectionResult = $data->query($sectionQuery);

                            if ($sectionResult->num_rows == 1) {
                                $sectionRow = $sectionResult->fetch_assoc();
                                $sectionName = $sectionRow["section_name"];
                            } else {
                                $sectionName = "Unknown Section";
                            }

                            $id = $row_table["announcement_id"];
                            $title = $row_table["announcement_title"];
                            $description = $row_table["description"];
                            $date = $row_table["announcement_post-date"];
                            $class_section = $row_table["announcement_class_section"];
                            ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $title; ?></td>
                                <td>
                                    <?php
                                    if (strlen($description) > 50) {
                                        echo substr($description, 0, 50) . "...";
                                    } else {
                                        echo $description;
                                    }
                                    ?>
                                </td>
                                <td><?php echo date("m/d/Y", strtotime($date)); ?></td>
                                <td><?php echo $posted_by; ?></td>
                                <td><?php echo $sectionName; ?></td>
                                <!-- <td><a href="<?php //echo $user_id; ?>/Admin-View-Announcement/<?//php echo $id; ?>"><button>View</button></a></td> -->
                                <td><a href="admin/announcement/edit/<?php echo $id; ?>"><button id="edit-btn">Edit</button></a>
                                </td>
                                <td><button id="delete-btn" onclick="confirmDelete(<?php echo $id; ?>)">Delete</button></td>
                            </tr>
                            <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="9">No Announcement</td>
                        </tr>
                    <?php }
                    mysqli_free_result($result);
                } else {
                    echo 'Error: ' . mysqli_error($data);
                }
                mysqli_close($data);
                ?>
            </table>
        </section>
    </main>
    <script>
        function confirmDelete(announcementId) {
            var confirmation = confirm("Are you sure you want to delete this announcement?");
            if (confirmation) {
                window.location.href = "admin/announcement/delete/" + announcementId;
            }
        }
    </script>
    <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
</body>
</html>