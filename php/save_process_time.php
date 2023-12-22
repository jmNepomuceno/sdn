<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

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

        $currentDate = date("Y-m-d H:i:s"); // Current date and time

        $sql = "UPDATE sdn_users SET user_lastLoggedIn=:curr_date, user_isActive='0' WHERE username=:username AND password=:password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['user_name'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $_SESSION['user_password'], PDO::PARAM_STR);
        $stmt->bindParam(':curr_date', $currentDate, PDO::PARAM_STR);
        $stmt->execute();
        
        echo $_SESSION['user_name'] . " " . $_SESSION['user_password'];
    }
    
    if($what === 'continue'){
        $sql = "SELECT hpercode,status,progress_timer,logout_date FROM incoming_referrals WHERE progress_timer!='' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $jsonString = json_encode($data);
        echo $jsonString;
    }

    
?>