<?php 
    session_start();
    include('../database/connection2.php');
    // fetch sdn hospitals and sdn users
    $sql = "SELECT * FROM sdn_hospital";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data_sdn_hospitals); echo '</pre>';

    $sql = "SELECT * FROM sdn_users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data_sdn_users); echo '</pre>';

    $sql = "SELECT hospital_code FROM sdn_users WHERE user_count=2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_users_count2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($data_sdn_users_count2); echo '</pre>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>