<?php
$required_role = "Teacher";
require_once dirname(__DIR__, 2) . '/auth.php';
// Database connectivity
require_once dirname(__DIR__, 2) . '/db_connection.php';
$active = "class-section";

// Initialize an empty array to store class sections
$class_sections = array();

// Query to fetch class sections with adviser name from users table
$sql = "SELECT 
            cs.*, 
            CONCAT(
                u.user_last_name, ', ',
                u.user_first_name, ' ',
                COALESCE(u.user_middle_name, '')
            ) AS adviser_name
        FROM `class-sections` cs
        LEFT JOIN users u
            ON cs.adviser_id = u.user_id
        WHERE cs.adviser_id = $user_id";

// Execute the query
$result = mysqli_query($data, $sql);

// Check if the query was successful
if ($result) {
    // Fetch rows from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Add each announcement to the $class_sections array
        $class_sections[] = $row;
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Query failed
    echo "Error: " . mysqli_error($data);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">

    <title>Class Section Management</title>
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/class-section-mgmt.css">
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
        <h1>Class Section Management</h1>

        <div class="btn">
            <a href="teacher/dashboard">
                <button id="back-btn">Back</button>
            </a>
            <a href="teacher/class-section/add">
                <button id="add-class-section-btn">Add Class Section</button>
            </a>
        </div>


        <section class="class-section-management">
            <?php if (isset($_SESSION['success'])) { ?>
                <div style="color:green; background:#e6ffe6; padding:10px; margin-bottom:10px;">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php } ?>
            <table id="class-section">
                <tr>
                    <th>Class Section ID</th>
                    <th>Section Name</th>
                    <th>Term</th>
                    <th>School Year</th>
                    <th>Adviser</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <!-- APPLY FOREACH CLASS SECTION, SHOW ROW OF DETAILS -->
                <?php
                // Display fetched announcements
                foreach ($class_sections as $class_section) {
                    $adviserName = !empty($class_section['adviser_name']) ? $class_section['adviser_name'] : 'No Adviser Assigned';
                    echo "<tr>";
                    echo "<td>{$class_section['class-section_id']}</td>";
                    echo "<td>{$class_section['section_name']}</td>";
                    echo "<td>{$class_section['term_no']}</td>";
                    echo "<td>{$class_section['school_year']}</td>";
                    echo "<td>{$adviserName}</td>";
                    echo "<td><a href='teacher/class-section/edit/{$class_section['class-section_id']}'><button id='edit-btn'>Edit</button></a></td>";
                    echo "<td><button id='delete-btn' onclick='confirmDelete({$class_section['class-section_id']})'>Delete</button></td>";
                    echo "</tr>";
                }
                ?>

            </table>
    </main>

    <script>
        function confirmDelete(sectionId) {
            var confirmation = confirm("Are you sure you want to delete this section?");
            if (confirmation) {
                window.location.href = "teacher/class-section/delete/" + sectionId;
            }
        }
    </script>

       <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>

</html>