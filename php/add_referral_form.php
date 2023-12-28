<?php 

    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

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
    $hours = $dateTime->format("H");
    $minutes = $dateTime->format("i");
    $seconds = $dateTime->format("s");

    // FOR NAMING OF THE REFERENCE NUMBER DEPENDS ON WHAT HOSPITAL, BGH WILL REFER TO
    $referTo = filter_input(INPUT_POST, 'refer_to');
    $sql_temp = "SELECT hospital_municipality_code FROM sdn_hospital WHERE hospital_name = :refer_to";
    $stmt_temp = $pdo->prepare($sql_temp);
    $stmt_temp->bindParam(':refer_to', $referTo, PDO::PARAM_STR);
    $stmt_temp->execute();
    $data_municipality_code = $stmt_temp->fetch(PDO::FETCH_ASSOC);

    // reference now the municipality code to get the municipality name from city table
    $sql_temp = "SELECT municipality_description FROM city WHERE municipality_code=:id ";
    $stmt_temp = $pdo->prepare($sql_temp); 
    $stmt_temp->bindParam(':id', $data_municipality_code['hospital_municipality_code'], PDO::PARAM_STR);
    $stmt_temp->execute();
    $data_municipality_desc = $stmt_temp->fetch(PDO::FETCH_ASSOC);

    $inputString = $_POST['refer_to'];
    $words = explode(' ', $inputString);
    $firstLetters = array_map(function ($word) {
        return ucfirst(substr($word, 0, 1));
    }, $words);
    $abbreviation = implode('', $firstLetters);

    // sql variable
   // R3-BTN-LIMAY-FCSH-2023-12-06
   if($data_municipality_desc['municipality_description'] === "CITY OF BALANGA (Capital)"){
        $data_municipality_desc['municipality_description'] = "BALANGA";
   }
    $reference_num = 'R3-BTN-'. $data_municipality_desc['municipality_description'] . '-' . $abbreviation . '-' . $year . '-' . $month . '-' . $day;
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
        // echo "success";
    } else {
        // Statement did not execute properly
        // You can handle the error or display an error message
        $errorInfo = $stmt->errorInfo();
        // echo "Error: " . $errorInfo[2];
    }
    
    // updating for history log
    $act_type = 'pat_defer';
    $action = 'Outgoing Patient: ';
    $pat_name = $patlast . ' ' . $patfirst . ' ' . $patmiddle;
    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $global_single_hpercode, PDO::PARAM_STR);
    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
    $stmt->bindParam(3, $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
    $stmt->bindParam(5, $action, PDO::PARAM_STR);
    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
    $stmt->bindParam(7, $_SESSION['user_name'], PDO::PARAM_STR);

    $stmt->execute();
?>