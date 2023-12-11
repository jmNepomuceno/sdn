<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    $timer = $_POST['timer'];
    $currentDateTime = date('Y-m-d H:i:s');

    // update the status of the patient in the database and set the final progressed time.
    // $sql = "UPDATE incoming_referrals SET status='Approved' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    if($_POST['action'] === "Approve"){
        $sql = "UPDATE incoming_referrals SET status='Approved' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET status='Deferred' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $sql_b = "UPDATE incoming_referrals SET final_progressed_timer='". $_POST['timer']."' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt_b = $pdo->prepare($sql_b);
    $stmt_b->execute();

    // update the approved_details and set the time of approval on the database
    if($_POST['action'] === "Approve"){
        $sql = "UPDATE incoming_referrals SET approval_details='". $_POST['approve_details']."', approved_time='". $currentDateTime ."' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET deferred_details='". $_POST['approve_details']."', deferred_time='". $currentDateTime ."' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $index = 0;
    $index_to_remove = 0;

    for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        if($_SESSION["process_timer"][$i]['global_single_hpercode'] === $_POST['global_single_hpercode']){
            $index = $i;
        };
    }

    // update the session process_timer for whenever reloading/refreshing the page/web
    $keys = array_keys($_SESSION["process_timer"]);
    $indexToDelete = $index; // Index 1 corresponds to 'key2'

    array_splice($keys, $indexToDelete, 1);

    $_SESSION["process_timer"] = array_intersect_key($_SESSION["process_timer"], array_flip($keys));

    // Reindex the array numerically
    $_SESSION["process_timer"] = array_values($_SESSION["process_timer"]);

    $temp_session = json_encode($_SESSION["process_timer"] , JSON_NUMERIC_CHECK);
    // echo $temp_session;

    //get all the pending or on-process status on the database to populate the data table after the approval
    $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  


    $jsonString = json_encode($data);
    echo $jsonString;


    // update also the status of the patient on the hperson table
    $sql = "SELECT type FROM incoming_referrals WHERE hpercode='". $_POST['global_single_hpercode'] ."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_POST['action'] === "Approve"){
        $sql = "UPDATE hperson SET status='Approved', type='". $data['type'] ."' WHERE hpercode='". $_POST['global_single_hpercode']."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }else{
        $sql = "UPDATE hperson SET status='Deferred', type='". $data['type'] ."' WHERE hpercode='". $_POST['global_single_hpercode']."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
?>



