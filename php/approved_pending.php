<?php
    session_start();
    include("../database/connection2.php");

    $timer = $_POST['timer'];

    $sql = "UPDATE incoming_referrals SET status='Approved' WHERE hpercode='". $_POST['hpercode']."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql_b = "UPDATE incoming_referrals SET final_progressed_timer='". $_POST['timer']."' WHERE hpercode='". $_POST['hpercode']."' ";
    $stmt_b = $pdo->prepare($sql_b);
    $stmt_b->execute();

    // $_SESSION["process_timer"]
    $index = 0;
    $index_to_remove = 0;

    for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        if($_SESSION["process_timer"][$i]['pat_clicked_code'] === $_POST['hpercode']){
            $index = $i;
        };
    }

    // FIX THE FUCKING INDEXING OF ALL THE ROWS IN THE TABLE, ADJUST MALALA
    $index_to_remove = $index;
    if($index_to_remove === 0){
        for($i = 1; $i < count($_SESSION["process_timer"]); $i++){
            $_SESSION["process_timer"][$i]['table_index'] = intVal($_SESSION["process_timer"][$i]['table_index']) - 1;
        }
    }else{
        for($i = $index_to_remove; $i < count($_SESSION["process_timer"]); $i++){
            $_SESSION["process_timer"][$i]['table_index'] = intVal($_SESSION["process_timer"][$i]['table_index']) - 1;
        }
    }

    $keys = array_keys($_SESSION["process_timer"]);
    $indexToDelete = $index; // Index 1 corresponds to 'key2'

    array_splice($keys, $indexToDelete, 1);

    $_SESSION["process_timer"] = array_intersect_key($_SESSION["process_timer"], array_flip($keys));

    // Reindex the array numerically
    $_SESSION["process_timer"] = array_values($_SESSION["process_timer"]);

    $temp_session = json_encode($_SESSION["process_timer"] , JSON_NUMERIC_CHECK);
    echo $temp_session;
?>