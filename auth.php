<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /EPWD/Login");
    exit();
}

// Session variables
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'] ?? '';

// Optional role restriction
if (isset($required_role) && $role !== $required_role) {
    header("Location: /EPWD/Login");
    exit();
}
?>