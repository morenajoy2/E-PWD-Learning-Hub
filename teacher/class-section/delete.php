<?php
$required_role = "Teacher";

require_once dirname(__DIR__,2) . '/db_connection.php';
require_once dirname(__DIR__,2) . '/auth.php';

    if (!isset($_GET['class_section_id'])) {
        die("Invalid request");
    }

    $id = $_GET['class_section_id'];

    $sql = "DELETE FROM `class-sections`
            WHERE `class-section_id` = $id";

    $result = $data->query($sql);

if ($result) {
    $_SESSION['success'] = "Class section deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete class section.";
}

    header("location: /EPWD/teacher/class-section/management");
    exit()
?>
