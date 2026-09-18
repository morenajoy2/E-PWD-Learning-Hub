<?php  
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "epwd";

    // to connect the databases in the backend
    $data = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    if($data === false){
        die("Connection Error");
    }
?>   