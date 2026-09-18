<?php
$required_role = "Student";
require_once dirname(__DIR__, 2) . '/auth.php';
$active = "event";
require_once dirname(__DIR__, 2) . '/db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="/EPWD/">

    <title>Event Calendar - EmPoWeReD Learning Hub</title>
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/calendar.css">
    <link rel="icon" type="image/x-icon" href="./images/epwd-favicon.png">

    <!-- CSS for full calendar --> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
    <!-- JS for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- JS for full calendar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <!-- Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        main {
            margin-bottom: 140px;
            padding-bottom: 20px;
            min-height: calc(100vh - 240px);
            position: relative;
        }

        #calendar {
            min-height: 650px;
            position: relative;
        }

        #calendar .fc-view,
        #calendar .fc-scroller {
            min-height: 650px;
            /* padding-bottom: 120px !important; */
        }

        footer.footer {
            background-color: #000000;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
            font-size: 14px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            z-index: 10;
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

        #back-btn {
            border-radius: 10px;
        }
    </style>
</head>
<body>
        <?php require_once dirname(__DIR__, 2) . '/navbar.php'; ?>

    <main>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 text-align="center">Event Monthly Calendar</h1>
                </br>
                <a href="./student/event/index.php">
                    <button id="back-btn">Back</button>
                </a>
                </br> </br>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    </main>
    <script>

$(document).ready(function() {
            display_events();
        }); 

        function display_events() {
            var events = new Array();
            $.ajax({
                url: 'display_event.php',  
                dataType: 'json',
                success: function (response) {
                    var result = response.data;
                    $.each(result, function (i, item) {
                        events.push({
                            title: result[i].title,
                            date: result[i].date,
                            color: result[i].color,
                        }); 	
                    });
                    var calendar = $('#calendar').fullCalendar({
                        defaultView: 'month',
                        timeZone: 'local',
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month'
                        },
                        events: events
                    }); 
                },
                error: function (xhr, status) {
                    alert(response.msg);
                }
            });
        }

    </script>
    

        <?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>


</body>
</html>


