<?php
    include("../database/connection2.php");

    $sdn_username = $_POST['sdn_username'];
    $sdn_password = $_POST['sdn_password'];

    if($sdn_username == "admin" && $sdn_password == "admin"){
        echo "main.php";
    }
    
?>