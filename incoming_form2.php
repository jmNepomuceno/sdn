<?php
    // ini_set('session.save_path', 'C:/Web/eSDN/session_path');
    // echo __DIR__ . "/session_path"; 
    
    ini_set('session.gc_probability', 0);
    session_start();

    include('../database/connection2.php');
    if($_SESSION['hospital_code'] === '1111'){
        $mcc_passwords = json_encode($_SESSION['mcc_passwords']);
    }else{
        $mcc_passwords = json_encode("");
    }

    // hold the data of the running timer opon logout
    $post_value_reload = '';

    $sql = "SELECT * FROM incoming_referrals WHERE progress_timer IS NOT NULL AND refer_to = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["hospital_name"]]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) > 0){
        $_SESSION['post_value_reload'] = 'true';
        $post_value_reload = $_SESSION['post_value_reload'];
    }


    // holds the data upon logging out
    $sql = "SELECT * FROM incoming_referrals WHERE logout_date!='null' AND refer_to = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["hospital_name"]]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $logout_data = json_encode($data);

    // for interdepartamental
    if($_SESSION['running_hpercode'] != null || $_SESSION['running_hpercode'] != ""){
        $sql = "SELECT status_interdept FROM incoming_referrals WHERE hpercode = :hpercode";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':hpercode' => $_SESSION['running_hpercode']]);
        $status_interdept = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prepare and execute the second query
        $sql = "SELECT department FROM incoming_interdept WHERE hpercode = :hpercode";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':hpercode' => $_SESSION['running_hpercode']]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        // Concatenate status and department if both are set
        $current_pat_status = "";
        if ($status_interdept && $department) {
            $current_pat_status = $status_interdept['status_interdept'] . ' - ' . strtoupper($department['department']);
        }
    }

    // $sql = "UPDATE incoming_referrals SET status='Pending', final_progressed_timer=null, pat_class=null  WHERE hpercode='PAT000009'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status='Pending' WHERE hpercode='PAT000009'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // ******************************************************************

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000023'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000031'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000032'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000007'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000008'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status='Pending' WHERE hpercode='PAT000007'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status='Pending' WHERE hpercode='PAT000008'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status='Pending' WHERE hpercode='PAT000033'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status='Pending' WHERE hpercode='PAT000003'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "DELETE FROM incoming_interdept";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "DELETE FROM reject_interdept";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status='Pending', reception_time=null, final_progressed_timer=null, approved_time=null, approval_details=null, status_interdept=null, sent_interdept_time=null, last_update=null, pat_class=null WHERE hpercode='PAT000034'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "DELETE FROM incoming_referrals WHERE hpercode='PAT000031'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE hperson SET status=null, referral_id=null, type=null WHERE hpercode='PAT000031'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // echo $_SESSION['running_timer'] . "----";
    // echo $_SESSION['running_bool'] . "----";
    // echo $_SESSION['running_hpercode'] . "----";
    // echo $_SESSION['running_index'] . "----";
    // echo $_SESSION['datatable_index'] . "----";

    // echo '<pre>'; print_r($_SESSION['approval_details_arr']); echo '</pre>';

    $sql = "SELECT status_interdept FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to=? ORDER BY date_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["hospital_name"]]);
    $status_interdept_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // echo $_SESSION['datatable_index'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

     <?php require "../header_link.php" ?>
    <link rel="stylesheet" href="../css/incoming_form.css">
</head>
<body>

    <?php
        if(isset($_SESSION['running_hpercode']) && ($_SESSION['running_hpercode'] != null || $_SESSION['running_hpercode'] != "")) {
            echo '<input id="pat-curr-stat-input" type="hidden" name="pat-curr-stat-input" value="' . $current_pat_status . '">';
            echo '<input id="running-index" type="hidden" name="running-index" value="' .  $_SESSION["running_index"] . '">';
        }
    ?>

    <div class="incoming-container">
        <h1>Incoming Referral Patients</h1>
        <div class="search-main-div">
            <div class="refer-no-div">
                <label for="incoming-referral-no-search">Referral No.</label>
                <input id="incoming-referral-no-search" type="textbox">
            </div>
        
            <div class="lname-search-div">
                <label for="incoming-last-name-search">Last Name</label>
                <input id="incoming-last-name-search" type="textbox">
            </div>

            <div class="fname-search-div">
                <label  for="incoming-first-name-search">First Name</label>
                <input id="incoming-first-name-search" type="textbox">
            </div>

            <div class="mname-search-div">
                <label  for="incoming-middle-name-search">Middle Name</label>
                <input id="incoming-middle-name-search" type="textbox">
            </div>

            <div class="caseType-search-div">
                <label for="incoming-type-select">Case Type</label>
                <select id='incoming-type-select'>
                    <?php 
                        $stmt = $pdo->prepare('SELECT classifications FROM classifications');
                        $stmt->execute();

                        echo '<option value=""> None </option>';
                        while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                            echo '<option value="' , $data['classifications'] , '">' , $data['classifications'] , '</option>';
                        } 
                    ?>
                </select>
            </div>


            <div class="agency-search-div">
                <label id="agency-search" for="incoming-agency-select">Agency</label>
                <select id='incoming-agency-select'>
                   <?php 
                    $stmt = $pdo->prepare('SELECT hospital_name FROM sdn_hospital');
                    $stmt->execute();
            
                    echo '<option value=""> None </option>';
                    while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo '<option value="' , $data['hospital_name'] , '">' , $data['hospital_name'] , '</option>';
                    } 
                   ?>
                </select>
            </div>


            <div class="status-search-div">
                <label for="incoming-status-select">Status</label>
                <select id='incoming-status-select'>
                    <option value="default">Select</option>
                    <option value="Pending">Pending</option>
                    <option value="All"> All</option>
                    <option value="On-Process"> On-Process</option>
                    <option value="Deferred"> Deferred</option>
                    <option value="Approved"> Approved</option>
                    <option value="Cancelled"> Cancelled</option>
                    <option value="Arrived"> Arrived</option>
                    <option value="Checked"> Checked</option>
                    <option value="Admitted"> Admitted</option>
                    <option value="Discharged"> Discharged</option>
                    <option value="For follow"> For follow up</option>
                    <option value="Referred"> Referred Back</option>
                </select>
            </div>

            <div class="search-clear-btns-div">
                <button id='incoming-clear-search-btn'>Clear</button>
                <button id='incoming-search-btn'>Search</button>
            </div>
        </div>

        <section class="incoming-table">

            <table id="myDataTable" class="display table table-bordered custom-search-modal" style="width: 100%; border-spacing: -1px;">
                <thead>
                    <tr class="text-center">
                        <th id="refer-no">Reference No. </th>
                        <th>Patient's Name</th>
                        <th>Type</th>
                        <th>Agency</th>
                        <th>Date/Time</th>
                        <th>Response Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="incoming-tbody">
                <?php
                        // get the classification names
                        $sql = "SELECT classifications FROM classifications";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $data_classifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $color = ["#d77707" , "#22c45e" , "#0368a1" , "#cf3136" , "#919122" , "#999966" , "#6666ff"];
                        $dynamic_classification = [];
                        for($i = 0; $i < count($data_classifications); $i++){
                            $dynamic_classification[$data_classifications[$i]['classifications']] = $color[$i];
                        }

                        // SQL query to fetch data from your table
                        try{
                            $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to=? ORDER BY date_time ASC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$_SESSION["hospital_name"]]);
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // echo count($data);
                            $jsonData = json_encode($data);

                            $index = 0;
                            $previous = 0;
                            $loop = 0;
                            // Loop through the data and generate table rows
                            foreach ($data as $row) {
                                $type_color = $dynamic_classification[$row['type']];
                                if($previous == 0){
                                    $index += 1;
                                }else{
                                    if($row['reference_num'] == $previous){
                                        $index += 1;
                                    }else{
                                        $index = 1;
                                    }  
                                }
                                
                                // $style_tr = 'background:#33444d; color:white;';
                                $style_tr = '';
                                if($loop != 0 &&  $row['status'] === 'Pending'){
                                    $style_tr = 'opacity:0.5; pointer-events:none;';
                                }

                                // $waiting_time = "--:--:--";
                                $date1 = new DateTime($row['date_time']);
                                $waiting_time_bd = "";
                                if($row['reception_time'] != null){
                                    $date2 = new DateTime($row['reception_time']);
                                    $waiting_time = $date1->diff($date2);

                                    // if ($waiting_time->days > 0) {
                                    //     $differenceString .= $waiting_time->days . ' days ';
                                    // }

                                    $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

                                }else{
                                    $waiting_time_bd = "00:00:00";
                                }

                                if($row['reception_time'] == ""){
                                    $row['reception_time'] = "00:00:00";
                                }

                                if($row['status_interdept'] != "" && $row['status_interdept'] != null){
                                    $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."' ORDER BY id DESC LIMIT 1";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                                    $row['status'] = $row['status_interdept'] . " - " . strtoupper($data['department']);
                                }
                                // processed time = progress time ng admin + progress time ng dept
                                // maiiwan yung timer na naka print, once na send na sa interdept
                                
                                $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode=:hpercode";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':hpercode', $row['hpercode'], PDO::PARAM_STR);
                                $stmt->execute();
                                $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                $total_time = "00:00:00";
                                if($interdept_time){
                                    if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
                                        list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
                                        list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

                                        // Create DateTime objects in UTC with the provided hours, minutes, and seconds
                                        $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
                                        $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

                                        // Calculate the total milliseconds
                                        $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

                                        // Create a new DateTime object in UTC with the total milliseconds
                                        $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

                                        // Format the result in UTC time "HH:mm:ss"
                                        $total_time = $newDate->format('H:i:s');
                                    }
                                }else{
                                    $interdept_time[0]['final_progress_time'] = "00:00:00";
                                    $row['sent_interdept_time'] = "00:00:00";
                                    // $total_time = $row['final_progressed_timer'];
                                }

                                // echo($total_time);

                                if($row['approved_time'] == ""){
                                    $row['approved_time'] = "0000-00-00 00:00:00";
                                }

                                if($interdept_time[0]['final_progress_time'] == ""){
                                    $interdept_time[0]['final_progress_time'] = "00:00:00";
                                }

                                if($row['sent_interdept_time'] == ""){
                                    $row['sent_interdept_time'] = "00:00:00";
                                }

                                $stopwatch = "00:00:00";
                                if($row['sent_interdept_time'] == "00:00:00"){
                                    if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
                                        $stopwatch  = $_SESSION['running_timer'];
                                    }
                                }else{
                                    $stopwatch  = $row['sent_interdept_time'];
                                }

                                // for sensitive case
                                $pat_full_name = ""; 
                                if($row['sensitive_case'] === 'true'){
                                    $pat_full_name = "
                                        <div class='pat-full-name-div'>
                                            <button class='sensitive-case-btn'> <i class='sensitive-lock-icon fa-solid fa-lock'></i> Sensitive Case </button>
                                            <input class='sensitive-hpercode' type='hidden' name='sensitive-hpercode' value= '" . $row['hpercode'] . "'>
                                        </div>
                                    ";
                                }else{
                                    // $pat_full_name = $row['patlast'] . ", " . $row['patfirst'] . " " . $row['patmiddle'];
                                    $pat_full_name = "
                                        <div class='pat-full-name-div'>
                                            <button class='sensitive-case-btn' style='display:none;'> <i class='sensitive-lock-icon fa-solid fa-lock'></i> Sensitive Case </button>
                                            <p> " . $row['patlast'] . " , " . $row['patfirst'] . "  " . $row['patmiddle'] . "</p>
                                            <input class='sensitive-hpercode' type='hidden' name='sensitive-hpercode' value= '" . $row['hpercode'] . "'>
                                        </div>
                                    ";
                                }

                                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                                        <td id="dt-patname">' . $pat_full_name . '</td>
                                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                                        <td id="dt-phone-no">
                                            <div class="">
                                                <p> Referred by: ' . $row['referred_by'] . '  </p>
                                                <p> Landline: ' . $row['landline_no'] . ' </p>
                                                <p> Mobile: ' . $row['mobile_no'] . ' </p>
                                            </div>
                                        </td>
                                        <td id="dt-turnaround"> 
                                            <i class="accordion-btn fa-solid fa-plus"></i>

                                            <p class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </p>
                                            <p class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</p>
                                            <p class="sdn-proc-time-lbl"> SDN Processed: '. $row['sent_interdept_time'] .'</p>
                                            
                                            <div class="breakdown-div">
                                                <p class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</p>
                                                <p class="processed-time-lbl"> Total Processed: '.$total_time.'  </p>  
                                                <p> Approval: '.$row['approved_time'] .'  </p>  
                                                <p> Deferral: 0000-00-00 00:00:00  </p>  
                                                <p> Cancelled: 0000-00-00 00:00:00  </p>  
                                                <p> Arrived: 0000-00-00 00:00:00  </p>  
                                                <p> Checked: 0000-00-00 00:00:00  </p>  
                                                <p> Admitted: 0000-00-00 00:00:00  </p>  
                                                <p> Discharged: 0000-00-00 00:00:00  </p>  
                                                <p> Follow up: 0000-00-00 00:00:00  </p>  
                                                <p> Ref. Back: 0000-00-00 00:00:00  </p>  
                                            </div>
                                        </td>
                                        <td id="dt-stopwatch">
                                            <div id="stopwatch-sub-div">
                                                Processing: <span class="stopwatch">'.$stopwatch.'</span>
                                            </div>
                                        </td>
                                        
                                        <td id="dt-status">
                                            <div> 
                                                <p class="pat-status-incoming">' . $row['status'] . '</p>';
                                                if ($row['sensitive_case'] === 'true') {
                                                    echo '<i class="pencil-btn fa-solid fa-pencil" style="pointer-events:none; opacity:0.3; color:#cc9900;"></i>';
                                                }else{
                                                    echo'<i class="pencil-btn fa-solid fa-pencil" style="color:#cc9900;"></i>';
                                                }
                                                
                                                echo '<input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                                            </div>
                                        </td>
                                    </tr>';

                               
                                $previous = $row['reference_num'];
                                $loop += 1;
                            }

                            // Close the database connection
                            $pdo = null;
                        }
                        catch(PDOException $e){
                            echo "asdf";
                        }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- MODAL -->
    
    <div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl pendingModalSize" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button>Print</button>
                    <button id="close-pending-modal" data-bs-dismiss="modal">Close</button> -->
                    PATIENT REFERRAL INFORMATION

                    <button id="proceed-ref-res">Proceed to Referral Response</button>
                </div>
                
                <div class="modal-body-incoming">
                    <div class="container">
                        <div class="left-div"> 
                        </div>
                        <div class="right-div">
                        </div>  
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- <button type="button" id="submit-modal-btn-incoming" class="btn btn-danger" data-bs-dismiss="modal">Submit</button> -->
                    <button type="button" id="close-modal-btn-incoming" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-incoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Please input at least one value in any field.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" data-bs-dismiss="modal" data-target=".sensitive-case-btn">OK</button>
                <button id="yes-modal-btn-incoming" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
                        
    <script src="../js/incoming_form2.js?v= <?php echo time(); ?>"></script>

    <script>                        
        var post_value_reload  = <?php echo json_encode($post_value_reload); ?>;

        var running_timer_var = <?php echo json_encode(floatval($_SESSION['running_timer'])); ?>;
        var running_bool_var = <?php echo json_encode($_SESSION['running_bool']); ?>;
        var running_startTime_var = <?php echo json_encode($_SESSION['running_startTime']); ?>;
        var previous_loadcontent = <?php echo json_encode($_SESSION['current_content']); ?>;

        var mcc_passwords = <?php echo $mcc_passwords; ?>;

        var jsonData = <?php echo $jsonData; ?>;
        var login_data = "<?php echo $_SESSION['login_time']; ?>";

        var status_interdept_arr = <?php echo json_encode($status_interdept_arr); ?>;

        var current_dataTable_index = <?php echo json_encode($_SESSION['datatable_index']); ?>;
    </script>
</body>
</html>

<?php $_SESSION['current_content'] = 'incoming_ref'; ?>

<!-- Interdepartment: Surgery - Status approved - div
491
VM7797:491 Uncaught TypeError: Cannot set properties of undefined (setting 'textContent')
    at updateTimer (<anonymous>:491:80) -->