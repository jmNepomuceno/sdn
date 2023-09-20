<?php 
    include("../database/connection2.php");

    $hospital_code = $_POST['hospital_code'];
    $OTP = $_POST['OTP'];

    $sql = "UPDATE sdn_hospital SET hospital_OTP = :OTP WHERE hospital_code=:hospital_code";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':hospital_code', $hospital_code, PDO::PARAM_INT);
    $stmt->bindParam(':OTP', $OTP, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'updated';
    }
?>