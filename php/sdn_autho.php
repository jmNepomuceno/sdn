<?php
    include("../database/connection2.php");
    include("./csrf/session.php");

    // echo $_SESSION['_csrf_token'];

    $hospital_code = $_POST['hospital_code'];
    $cipher_key =  $_SESSION['_csrf_token'];

    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $extension_name = $_POST['extension_name'];

    $user_name = $_POST['user_name'];
    $pass_word = $_POST['pass_word'];
    $confirm_pw = $_POST['confirm_password'];

    echo $hospital_code;
    echo $cipher_key;
    echo $last_name;
    echo $first_name;
    echo $middle_name;
    echo $extension_name;

    echo $user_name;
    echo $pass_word;

    if($confirm_pw == $pass_word){
        $sql = "INSERT INTO sdn_users (hospital_ID, user_lastname, user_firstname, user_middlename, user_extname, username, password)
                VALUES (?,?,?,?,?,?,?)";

        //user_type // user_count // user_isActive // user_created

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(1, $hospital_code, PDO::PARAM_INT);
        $stmt->bindParam(2, $last_name, PDO::PARAM_STR);
        $stmt->bindParam(3, $first_name, PDO::PARAM_STR);
        $stmt->bindParam(4, $middle_name, PDO::PARAM_STR);
        $stmt->bindParam(5, $extension_name, PDO::PARAM_STR);
        $stmt->bindParam(6, $user_name, PDO::PARAM_STR);
        $stmt->bindParam(7, $pass_word, PDO::PARAM_STR);

        $stmt->execute();
    }

    
?>