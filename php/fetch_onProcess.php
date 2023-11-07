<?php 
    session_start();
    include('../database/connection2.php');

    $temp = json_encode($_SESSION["process_timer"]);
    echo $temp;

?>