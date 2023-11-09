<?php
    session_start();
    include("../database/connection2.php");

    $sql = "UPDATE incoming_referrals SET status='Approved' WHERE hpercode='". $_POST['hpercode']."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // $_SESSION["process_timer"]
    $index = 0;

    for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        if($_SESSION["process_timer"][$i]['pat_clicked_code'] === $_POST['hpercode']){
            $index = $i;
        };
    }

    $keys = array_keys($_SESSION["process_timer"]);
    $indexToDelete = $index; // Index 1 corresponds to 'key2'

    array_splice($keys, $indexToDelete, 1);

    $_SESSION["process_timer"] = array_intersect_key($_SESSION["process_timer"], array_flip($keys));

?>