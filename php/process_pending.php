<?php
    session_start();
    include("../database/connection2.php");

    $hpercode = $_POST['hpercode'];
    $sql = "SELECT * FROM incoming_referrals WHERE hpercode='". $hpercode ."' ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $jsonString = $data;

    // echo '<pre>'; print_r($data); echo '</pre>';
    //if(count($data) === 1){
        // $jsonString = json_encode($data);
        // echo $jsonString;
    //}

    $sql = "SELECT * FROM hperson WHERE hpercode='". $hpercode ."' ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    // echo '<pre>'; print_r($data); echo '</pre>';
    $jsonString_2 = $data;

    $mergedObj = array_merge($jsonString, $jsonString_2);

    // FOR ADDRESS CODE CONVERTION
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // if the query is slow, remove the region/province/city/brgy code and directly save the name of the regions/province/city/brgy.
    // FROM REGION CODE TO REGION DESCRIPTION QUERY
    // permanent address
    $sql_province = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_province"] .'" ';
    $stmt_province = $pdo->prepare($sql_province);
    $stmt_province->execute();
    $data_province = $stmt_province->fetchAll(PDO::FETCH_ASSOC);
    
    $sql_city = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_municipality"] .'" ';
    $stmt_city = $pdo->prepare($sql_city);
    $stmt_city->execute();
    $data_city = $stmt_city->fetchAll(PDO::FETCH_ASSOC);

    $sql_brgy = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_barangay"] .'" ';
    $stmt_brgy = $pdo->prepare($sql_brgy);
    $stmt_brgy->execute();
    $data_brgy = $stmt_brgy->fetchAll(PDO::FETCH_ASSOC);

    $mergedObj[1]["pat_province"] = $data_province[0]['province_description'];
    $mergedObj[1]["pat_municipality"] = $data_city[0]['municipality_description'];
    $mergedObj[1]["pat_barangay"] = $data_brgy[0]['barangay_description'];

    // current address
    $sql_province_ca = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_curr_province"] .'" ';
    $stmt_province_ca = $pdo->prepare($sql_province_ca);
    $stmt_province_ca->execute();
    $data_province_ca = $stmt_province_ca->fetchAll(PDO::FETCH_ASSOC);
    
    $sql_city_ca = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_curr_municipality"] .'" ';
    $stmt_city_ca = $pdo->prepare($sql_city_ca);
    $stmt_city_ca->execute();
    $data_city_ca = $stmt_city_ca->fetchAll(PDO::FETCH_ASSOC);

    $sql_brgy_ca = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_curr_barangay"] .'" ';
    $stmt_brgy_ca = $pdo->prepare($sql_brgy_ca);
    $stmt_brgy_ca->execute();
    $data_brgy_ca = $stmt_brgy_ca->fetchAll(PDO::FETCH_ASSOC);

    $mergedObj[1]["pat_curr_province"] = $data_province_ca[0]['province_description'];
    $mergedObj[1]["pat_curr_municipality"] = $data_city_ca[0]['municipality_description'];
    $mergedObj[1]["pat_curr_barangay"] = $data_brgy_ca[0]['barangay_description'];

    // current workplace address
    if($mergedObj[1]["pat_work_province"] != "N/A"){
        $sql_province_cwa = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_work_province"] .'" ';
        $stmt_province_cwa = $pdo->prepare($sql_province_cwa);
        $stmt_province_cwa->execute();
        $data_province_cwa = $stmt_province_cwa->fetchAll(PDO::FETCH_ASSOC);
        
        $sql_city_cwa = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_work_municipality"] .'" ';
        $stmt_city_cwa = $pdo->prepare($sql_city_cwa);
        $stmt_city_cwa->execute();
        $data_city_cwa = $stmt_city_cwa->fetchAll(PDO::FETCH_ASSOC);

        $sql_brgy_cwa = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_work_barangay"] .'" ';
        $stmt_brgy_cwa = $pdo->prepare($sql_brgy_cwa);
        $stmt_brgy_cwa->execute();
        $data_brgy_cwa = $stmt_brgy_cwa->fetchAll(PDO::FETCH_ASSOC);

        $mergedObj[1]["pat_work_province"] = $data_province_cwa[0]['province_description'];
        $mergedObj[1]["pat_work_municipality"] = $data_city_cwa[0]['municipality_description'];
        $mergedObj[1]["pat_work_barangay"] = $data_brgy_cwa[0]['barangay_description'];
    }
    
    $finalJsonString = json_encode($mergedObj);
    echo $finalJsonString;
    
    // print mo lang lahat ng need i print sa incoming_form.js bukas. gege
    // gl hf tomorrow! :)))))) <333333
?>