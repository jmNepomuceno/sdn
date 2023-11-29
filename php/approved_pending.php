<?php
    session_start();
    include("../database/connection2.php");

    $timer = $_POST['timer'];

    // update the status of the patient in the database and set the final progressed time.
    $sql = "UPDATE incoming_referrals SET status='Approved' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql_b = "UPDATE incoming_referrals SET final_progressed_timer='". $_POST['timer']."' WHERE hpercode='". $_POST['global_single_hpercode']."' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt_b = $pdo->prepare($sql_b);
    $stmt_b->execute();

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

    // get all the pending or on-process status on the database to populate the data table after the approval
    $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  


    $jsonString = json_encode($data);
    echo $jsonString;

?>