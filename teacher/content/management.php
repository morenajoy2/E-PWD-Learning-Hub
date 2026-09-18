<?php
$required_role = "Teacher";
require_once dirname(__DIR__,2) . '/auth.php';
$active = "content";
// Database connectivity
require_once dirname(__DIR__,2) . '/db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Content Management</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/content-mgmt.css">
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

        #contents td {
            max-width: 200px;
            word-wrap: break-word;
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
    <?php require_once dirname(__DIR__,2) . '/navbar.php'; ?>
    <main>
        <h1>Learning Content Management</h1>
        <div class="btn">
            <a href="./teacher/dashboard">
                <button id="back-btn">Back</button>
            </a>
            <a href="teacher/content/add">
                <button id="add-content-btn">Add Content</button>
            </a>
        </div>

        <section class="content-management">
            <?php if (isset($_SESSION['success'])) { ?>
                <div style="color:green; background:#e6ffe6; padding:10px; margin-bottom:10px;">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php } ?>
            <table id="contents">
                <tr>
                    <th>Content ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Youtube Embed Link</th>
                    <th>Upload Date</th>
                    <th>Posted By</th>
                    <th>Audience</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <!-- APPLY FOREACH CONTENT, SHOW ROW OF DETAILS -->
                <?php
                // Fetch table from users
                $sql = "SELECT * FROM `learning-contents`
                    WHERE learning_content_posted_by = '$user_id'";
                $result = mysqli_query($data, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_table = mysqli_fetch_assoc($result)) {

                            // Retrieve the user's name from the 'users' table based on user_id
                            $var01 = $row_table['learning_content_posted_by'];
                            $userQuery = "SELECT user_first_name, user_middle_name, user_last_name FROM users WHERE user_id = $var01";
                            $userResult = $data->query($userQuery);

                            if ($userResult->num_rows == 1) {
                                $userRow = $userResult->fetch_assoc();
                                $posted_by = $userRow["user_last_name"] . ", " . $userRow["user_first_name"] . " " . $userRow["user_middle_name"];
                            } else {
                                $posted_by = "Unknown User";
                            }

                            // Retrieve the user's name from the 'users' table based on user_id
                            $var02 = $row_table['learning_content_class_section'];
                            $sectionQuery = "SELECT section_name FROM `class-sections` WHERE `class-section_id` = $var02";
                            $sectionResult = $data->query($sectionQuery);

                            if ($sectionResult->num_rows == 1) {
                                $sectionRow = $sectionResult->fetch_assoc();
                                $sectionName = $sectionRow["section_name"];
                            } else {
                                $sectionName = "Unknown Section";
                            }

                            $id = $row_table["learning_content_id"];
                            $title = $row_table["learning_content_title"];
                            $description = $row_table["learning_content_description"];
                            $youtube_link = $row_table["learning_content_youtube_embed"];
                            $date = $row_table["learning_content_upload_date"];
                            $class_section = $sectionName;
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
                                <th><?php
                                if (strlen($youtube_link) > 20) {
                                    echo substr($youtube_link, 0, 20) . "...";
                                } else {
                                    echo $youtube_link;
                                }
                                ?></th>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $posted_by; ?></td>
                                <td><?php echo $class_section; ?></td>

                                <td><a href="teacher/content/edit/<?php echo $id; ?>"><button
                                            id="edit-btn">Edit</button></a>
                                </td>
                                <td><button id="delete-btn" onclick="confirmDelete(<?php echo $id; ?>)">Delete</button></td>
                            </tr>
                            <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="10">No Learning Content</td>
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
        function confirmDelete(contentId) {
            var confirmation = confirm("Are you sure you want to delete this learning content?");
            if (confirmation) {
                window.location.href = "teacher/content/delete/" + contentId;
            }
        }
    </script>

        <?php require_once dirname(__DIR__,2) . '/footer.php'; ?>


</body>

</html>