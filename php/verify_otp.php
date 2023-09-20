<?php 
    include("../database/connection2.php");

    $otp_number = $_POST['otp_number'];
    $hospital_code = $_POST['hospital_code'];
    $verify = true;
    //FETCH THE WHOLE ROW
    $sql = 'SELECT * FROM sdn_hospital WHERE hospital_OTP="'. $otp_number .'" ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $user_OTP = $data[0]['hospital_OTP'];

    echo $user_OTP;
    echo $otp_number;

    if($user_OTP == $otp_number){
        //update the row with verified = TRUE
        $sql = "UPDATE sdn_hospital SET hospital_isVerified = :verify WHERE hospital_code=:hospital_code";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':hospital_code', $hospital_code, PDO::PARAM_INT);
        $stmt->bindParam(':verify', $verify, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            echo 'verified';
        }
    }else{
        echo "not verified";
    }
?>