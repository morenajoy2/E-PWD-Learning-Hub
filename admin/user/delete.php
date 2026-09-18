<?php
$required_role = "Admin";

require_once dirname(__DIR__,2) . '/db_connection.php';
require_once dirname(__DIR__,2) . '/auth.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM users WHERE user_id = $id";

$result = $data->query($sql);

if ($result) {
    $_SESSION['success'] = "User deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete user.";
}

header("Location: /EPWD/admin/user/management");
exit();
?>