<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');
    

    $_SESSION['running_timer'] = $_POST['timer']; // elapsedTime
    $_SESSION['running_bool'] = $_POST['running_bool'];
    $_SESSION['running_startTime'] = $_POST['startTime'];

    $_SESSION['running_hpercode'] = $_POST['hpercode'];
    $_SESSION['running_index'] = $_POST['index'];

    $_SESSION['datatable_index'] = $_POST['index'];

    $sql = "SELECT status_interdept FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $status_interdept_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($status_interdept_arr);
?>