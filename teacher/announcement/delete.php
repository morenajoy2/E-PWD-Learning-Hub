<?php
$required_role = "Teacher";

require_once dirname(__DIR__,2) . '/db_connection.php';
require_once dirname(__DIR__,2) . '/auth.php';


    if (!isset($_GET['announcement_id'])) {
        die("Invalid request");
    }

    $id = $_GET['announcement_id'];

    $sql = "DELETE FROM `announcements`
            WHERE `announcement_id` = $id";

    $result = $data->query($sql);

if ($result) {
    $_SESSION['success'] = "Announcement deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete announcement.";
}

    header("location: /EPWD/teacher/announcement/management");
    exit()
?>