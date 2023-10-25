<?php 
    session_start();
    include("../database/connection2.php");
    //SELECT * FROM incoming_referrals WHERE status='Pending' AND referred_by='Limay Medical Center'
    

    $ref_no = $_POST['ref_no'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $case_type = $_POST['case_type'];
    $agency = $_POST['agency'];
    // $status = $_POST['status'];
    $status = 'Pending';


    // referred_by='". $agency ."'
    // $sql = "SELECT * FROM incoming_referrals WHERE (reference_num LIKE '%". $agency ."%' OR patlast LIKE '%". $last_name ."%' OR patfirst LIKE '%". $first_name ."%' OR patmiddle LIKE '%". $middle_name ."%' OR type LIKE '%". $case_type ."%' OR referred_by LIKE '%". $agency ."%' OR status LIKE '%". $status ."%') AND refer_to='". $_SESSION["hospital_name"] ."'";

    // $sql = "SELECT * FROM incoming_referrals WHERE (type = '" . $case_type . "' OR referred_by = '" . $agency . "') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    //SELECT * FROM incoming_referrals WHERE type = 'ER' AND referred_by = 'Referred: Limay Medical Center' AND status = 'All' AND refer_to = 'Bataan General Hospital and Medical Center'

    $sql = "SELECT * FROM incoming_referrals WHERE ";

    $conditions = array();

    if (!empty($ref_no)) {
        $conditions[] = "reference_num LIKE '%". $ref_no ."%'";
    }

    if (!empty($last_name)) {
        $conditions[] = "patlast LIKE '%". $last_name ."%' ";
    }

    if (!empty($first_name)) {
        $conditions[] = "patfirst LIKE '%". $first_name ."%' ";
    }

    if (!empty($middle_name)) {
        $conditions[] = "patmiddle LIKE '%". $middle_name ."%' ";
    }

    if (!empty($case_type)) {
        $conditions[] = "type = '" . $case_type . "'";
    }

    if (!empty($agency)) {
        $conditions[] = "referred_by = '" . $agency . "'";
    }

    if (!empty($status)) {
        $conditions[] = "status = '" . $status . "'";
    }

    if (count($conditions) > 0) {
        $sql .= implode(" AND ", $conditions);
    } else {
        $sql .= "1";  // Always true condition if no input values provided.
    }
    
    $sql .= " AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    // $sql = "SELECT * FROM incoming_referrals WHERE type = 'ER' AND status = 'All' AND refer_to = 'Bataan General Hospital and Medical Center'";
    // echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    // echo count($data);

    $jsonString = json_encode($data);
    echo $jsonString;

    
?>