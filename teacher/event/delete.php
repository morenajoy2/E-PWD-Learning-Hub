<?php
$required_role = "Teacher";

require_once dirname(__DIR__,2) . '/db_connection.php';
require_once dirname(__DIR__,2) . '/auth.php';

    if (!isset($_GET['event_id'])) {
        die("Invalid request");
    }

    $id = $_GET['event_id'];

    $sql = "DELETE FROM `event`
            WHERE `event_id` = $id";

    $result = $data->query($sql);

if ($result) {
    $_SESSION['success'] = "Event deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete event.";
}

    header("location: /EPWD/teacher/event/management");
    exit()
?>