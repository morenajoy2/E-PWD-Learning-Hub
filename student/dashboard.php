<?php
$required_role = "Student";
require_once dirname(__DIR__) . '/auth.php';
$active = "dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
            <base href="/EPWD/">

    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/dashboard.css">
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

        h2 {
            margin: 20px 0px;
        }

        #quicklinks{
            width: 50%;
            margin: 16px 0px;
        }

        #quicklinks td, #quicklinks th {
            padding: 8px;
            text-align: left;
            font-size: 18px;
            font-weight: 600;
        }

        
    </style>
</head>
<body>
    <?php require_once dirname(__DIR__) . '/navbar.php'; ?>
    <main>
        <h1>Welcome, Student!</h1>

        <h2>Quicklinks</h2>
        
        <section class="quicklinks">
            <table id="quicklinks">
                <tr>
                    <td>Learning Contents</td>
                    <td><a href="./student/content/index"><button id="view-btn">View</button></a></td>
                </tr>
                <tr>
                    <td>Announcements</td>
                    <td><a href="./student/announcement/index"><button id="view-btn">View</button></a></td>
                </tr>
                <tr>
                    <td>Events</td>
                    <td><a href="./student/event/index"><button id="view-btn">View</button></a></td>
                </tr>
            </table>
    </main>
            <?php require_once dirname(__DIR__) . '/footer.php'; ?>


</body>
</html>


