<?php
    session_start();
    include("../database/connection2.php");

    $classification = $_POST['classification'];
    $what = $_POST['what'];
    echo $classification;

    if($what == 'add'){
        $sql = "INSERT INTO classifications (classifications) VALUES (?)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(1, $classification, PDO::PARAM_STR);
        $stmt->execute();
        echo "added";
    }else{
        $sql = "DELETE FROM classifications WHERE classifications=:classification";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':classification', $classification, PDO::PARAM_STR);
        $stmt->execute();
        echo "deleted";
        // $stmt->bindParam(1, $hospital_code, PDO::PARAM_INT);
        // $stmt->bindParam(2, $region, PDO::PARAM_STR);
    }
?>