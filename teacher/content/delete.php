<?php
$required_role = "Teacher";

require_once dirname(__DIR__,2) . '/db_connection.php';
require_once dirname(__DIR__,2) . '/auth.php';

if (!isset($_GET['content_id'])) {
    die("Invalid request");
}

$id = $_GET['content_id'];

$sql = "DELETE FROM `learning-contents`
        WHERE `learning_content_id` = $id";

$result = $data->query($sql);

if ($result) {
    $_SESSION['success'] = "Learning content deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete learning content.";
}

header("location: /EPWD/teacher/content/management");
exit();
?>