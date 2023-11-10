<?php 

    session_start();
    include("../database/connection2.php");

    // echo $_SESSION['hospital_code']; 
    // echo $_SESSION['hospital_name']; 
    // echo $_SESSION['hospital_email']; 
    // echo $_SESSION['hospital_landline']; 
    // echo $_SESSION['hospital_mobile']; 
    // echo $_SESSION['hospital_name']; 

    // echo $_SESSION['user_name']; 
    // echo $_SESSION['user_password']; 
    // echo $_SESSION['first_name']; 
    // echo $_SESSION['last_name']; 
    // echo $_SESSION['middle_name']; 
    // echo $_POST['type'];

    $code = $_POST['code'];

    $sql = "SELECT patlast, patfirst, patmiddle, patsuffix FROM hperson WHERE hpercode='". $code ."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $dateTime = new DateTime();
    // Format the DateTime object as needed
    $formattedDateTime = $dateTime->format("Y-m-d H:i:s");

    $year = $dateTime->format("Y");
    $month = $dateTime->format("m");
    $day = $dateTime->format("d");
    $hours = $dateTime->format("H") + 8;
    $minutes = $dateTime->format("i");
    $seconds = $dateTime->format("s");


    // sql variable
    $reference_num = 'R3-BTN-BGHMC-' . $year . '-' . $month . '-' . $day;
    $patlast = $data['patlast'];
    $patfirst = $data['patfirst'];
    $patmiddle = $data['patmiddle'];
    $patsuffix = $data['patsuffix'];

    $type = $_POST['type'];

    $referred_by = $_SESSION['hospital_name'];
    $landline_no = $_SESSION['hospital_landline'];
    $mobile_no = $_SESSION['hospital_mobile'];

    $referred_time =  $year . '/' .  $month . '/' .  $day  . ' - ' .  $hours . ':' .  $minutes . ':' .  $seconds;
    $status = 'Pending';

    /////////////////////////////////////////////////

    $refer_to = $_POST['refer_to'];
    $sensitive_case = $_POST['sensitive_case'];
    $parent_guardian = $_POST['parent_guardian'];
    $phic_member = $_POST['phic_member'];
    $transport = $_POST['transport'];
    $referring_doc = $_POST['referring_doc'];

    $complaint_history_input = $_POST['complaint_history_input'];
    $reason_referral_input = $_POST['reason_referral_input'];
    $diagnosis = $_POST['diagnosis'];


    $bp_input = $_POST['bp_input'];
    $hr_input = $_POST['hr_input'];
    $rr_input = $_POST['rr_input'];
    $temp_input = $_POST['temp_input'];
    $weight_input = $_POST['weight_input'];
    $pe_findings_input = $_POST['pe_findings_input'];

    // echo $reference_num;
    // echo $patlast;
    // echo $patfirst;
    // echo $patmiddle;
    // echo $patsuffix;
    // echo $type;
    // echo $referred_by;
    // echo $landline_no;
    // echo $mobile_no;
    // echo $referred_time;
    // echo $status;

    // echo "success";


    $sql = "INSERT INTO incoming_referrals (hpercode, reference_num, patlast, patfirst, patmiddle, patsuffix, type, referred_by, landline_no, mobile_no, date_time, status, refer_to, parent_guardian , phic_member, transport, referring_doctor, chief_complaint_history, reason, diagnosis, bp, hr, rr, temp, weight, pertinent_findings)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    // $sql = "INSERT INTO incoming_referrals (hpercode, reference_num, patlast, patfirst, patmiddle, patsuffix, type, referred_by, landline_no, mobile_no, date_time, status, refer_to, sensitive)
    // VALUES (?,?,?,?,?,?,?,?,?,?,?,?, ?,?)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $code, PDO::PARAM_STR);
    $stmt->bindParam(2, $reference_num, PDO::PARAM_STR);
    $stmt->bindParam(3, $patlast, PDO::PARAM_STR);
    $stmt->bindParam(4, $patfirst, PDO::PARAM_STR);
    $stmt->bindParam(5, $patmiddle, PDO::PARAM_STR);
    $stmt->bindParam(6, $patsuffix, PDO::PARAM_STR);
    $stmt->bindParam(7, $type, PDO::PARAM_STR);
    $stmt->bindParam(8, $referred_by, PDO::PARAM_STR);

    $stmt->bindParam(9, $landline_no, PDO::PARAM_STR);
    $stmt->bindParam(10, $mobile_no, PDO::PARAM_STR);

    $stmt->bindParam(11, $referred_time, PDO::PARAM_STR);
    $stmt->bindParam(12, $status, PDO::PARAM_STR);

    $stmt->bindParam(13, $refer_to, PDO::PARAM_STR);
    // $stmt->bindParam(14, $sensitive_case, PDO::PARAM_STR);

    $stmt->bindParam(14, $parent_guardian, PDO::PARAM_STR);
    $stmt->bindParam(15, $phic_member, PDO::PARAM_STR);
    $stmt->bindParam(16, $transport, PDO::PARAM_STR);
    $stmt->bindParam(17, $referring_doc, PDO::PARAM_STR);

    $stmt->bindParam(18, $complaint_history_input, PDO::PARAM_STR);
    $stmt->bindParam(19, $reason_referral_input, PDO::PARAM_STR);
    $stmt->bindParam(20, $diagnosis, PDO::PARAM_STR);

    $stmt->bindParam(21, $bp_input, PDO::PARAM_INT);
    $stmt->bindParam(22, $hr_input, PDO::PARAM_STR);
    $stmt->bindParam(23, $rr_input, PDO::PARAM_STR);

    $stmt->bindParam(24, $temp_input, PDO::PARAM_STR);
    $stmt->bindParam(25, $weight_input, PDO::PARAM_INT);
    $stmt->bindParam(26, $pe_findings_input, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Statement executed successfully
        // You can fetch data or perform further actions here
        echo "success";
    } else {
        // Statement did not execute properly
        // You can handle the error or display an error message
        $errorInfo = $stmt->errorInfo();
        echo "Error: " . $errorInfo[2];
    }
    
?>