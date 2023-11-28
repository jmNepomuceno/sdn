<?php
    session_start();
    include("../database/connection2.php");

    $what = $_POST['what'];
    if($what === 'save'){
        if(count($_SESSION["process_timer"]) >= 1){
            // echo "here";
            for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
                $sql = "UPDATE incoming_referrals SET progress_timer = '". $_SESSION["process_timer"][$i]['elapsedTime'] ."' , refer_to_code='". $_SESSION['hospital_code'] ."'  
                , logout_date='". $_POST['date'] ."' WHERE hpercode='". $_SESSION["process_timer"][$i]['global_single_hpercode'] ."'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
        }

        // $jsonString = json_encode($_SESSION["process_timer"]);
        // echo $jsonString;
        echo count($_SESSION["process_timer"]);
    }
    
    if($what === 'continue'){
        $sql = "SELECT hpercode,status,progress_timer FROM incoming_referrals WHERE progress_timer!=''";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $jsonString = json_encode($data);
        echo $jsonString;
    }

    
?>