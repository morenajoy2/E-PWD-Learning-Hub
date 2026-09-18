<?php
session_start();
require_once 'db_connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($data, $_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE user_email = '$email' LIMIT 1";
    $result = mysqli_query($data, $sql);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $error = "❌ User not registered. Please check your email.";
    } else {

        if ($password === $user['user_password']) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['user_type'];
            $_SESSION['name'] = $user['user_first_name'];

            if ($user['user_type'] == "Admin") {
                header("Location: admin/Dashboard");
            } elseif ($user['user_type'] == "Teacher") {
                header("Location: teacher/Dashboard");
            } elseif ($user['user_type'] == "Student") {
                header("Location: student/Dashboard");
            } elseif ($user['user_type'] == "Parent") {
                header("Location: parent/Dashboard");
            }
            exit();

        } else {
            $error = "❌ Incorrect password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/EPWD/">
    <title>Sign-In - EmPoWeReD Learning Hub</title>
    <link rel="icon" type="image/x-icon" href="images/epwd-favicon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('images/ePWD-bg.svg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        .sign-in-container {
            background: white;
            opacity: 95%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 280px;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #176429;
        }

        .error-message {
            color: red;
            display: none;
            /* Initially hide the error message */
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Sign In</button>
        <?php if (!empty($error)) { ?>
            <p style="color:red; text-align:center;">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    </form>

</body>

</html>