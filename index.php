<?php

// // Define the function to connect to MySQL using PDO
// function pdo_connect_mysql() {
//     $db_host = "localhost";
//     $db_user = "root";
//     $db_password = "";
//     $db_name = "epwd";

//     try {
//         // Create a new PDO instance
//         $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
//         // Set the PDO error mode to exception
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $pdo;
//     } catch (PDOException $e) {
//         // If PDO connection fails, show error message
//         die("Connection failed: " . $e->getMessage());
//     }
// }

// // Include functions and connect to the database using PDO MySQL
// $pdo = pdo_connect_mysql();

// // Page is set to home (home.php) by default, so when the visitor visits, that will be the page they see.
// $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'Login';
// // Include and show the requested page
// include $page . '.php';


?>
