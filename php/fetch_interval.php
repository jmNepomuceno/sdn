<?php 
    session_start();
    include("../database/connection2.php");

    $notif_value = 0;
    if($_POST['from_where'] == 'bell'){
        try{
            $sql = "SELECT status FROM incoming_referrals WHERE status='Pending' AND refer_to='". $_SESSION["hospital_name"] . "'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $notif_value = count($data);
            echo $notif_value;
        }catch(PDOException $e){
            echo $notif_value;
        }
    }else if($_POST['from_where'] == 'incoming'){
        try{
            $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $jsonString = json_encode($data);

            echo $jsonString;
            
        }catch(PDOException $e){
            echo $notif_value;
        }
    }
    

    
    
?>