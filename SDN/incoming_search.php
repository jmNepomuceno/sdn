<?php 
    session_start();
    include("../database/connection2.php");

    $from_where = $_POST['where'];

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

    if($from_where === "search"){
        $ref_no = $_POST['ref_no'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $case_type = $_POST['case_type'];
        $agency = $_POST['agency'];
        $status = $_POST['status'];
        // $status = 'Pending';
        if(isset($_POST['hpercode_arr'])){
            $_SESSION['fifo_hpercode'] = $_POST['hpercode_arr'];   
        }

        $sql = "SELECT * FROM incoming_referrals WHERE ";

        $conditions = array();
        $others = false;

        if (!empty($ref_no)) {
            $conditions[] = "reference_num LIKE '%". $ref_no ."%'";
            $others = true;
        }

        if (!empty($last_name)) {
            $conditions[] = "patlast LIKE '%". $last_name ."%' ";
            $others = true;
        }

        if (!empty($first_name)) {
            $conditions[] = "patfirst LIKE '%". $first_name ."%' ";
            $others = true;
        }

        if (!empty($middle_name)) {
            $conditions[] = "patmiddle LIKE '%". $middle_name ."%' ";
            $others = true;
        }

        if (!empty($case_type)) {
            $conditions[] = "type = '" . $case_type . "'"; 
            $others = true;
        }

        if (!empty($agency)) {
            $conditions[] = "referred_by = '" . $agency . "'";
            $others = true;
        } 

        if($status != "default" && $status!="All"){
            $conditions[] = "status = '" . $status . "'";
            $others = false;
        }

        if (count($conditions) > 0) {
            $sql .= implode(" AND ", $conditions);
        } else {
            $sql .= "1";  // Always true condition if no input values provided.
        }

        if($_POST['where_type'] == 'incoming'){
            $sql .= " AND refer_to = '" . $_SESSION["hospital_name"] . "' ORDER BY date_time DESC";
        }else{
            $sql .= " AND referred_by = '" . $_SESSION["hospital_name"] . "' ORDER BY date_time DESC";
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // $jsonString = json_encode($data);
        // echo $jsonString;

        
        $index = 0;
        $previous = 0;
        $loop = 0;
        $accord_index = 0;
        // Loop through the data and generate table rows`

        $sql = "SELECT * FROM incoming_referrals WHERE status='On-Process' AND refer_to = '" . $_SESSION["hospital_name"] . "' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $on_process = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // for immediate approval
        foreach ($on_process as $row) {
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
   
            $sql = "SELECT department, final_progress_time, interdept_status FROM incoming_interdept WHERE hpercode=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row['hpercode']]);
            $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $total_time = "00:00:00";
            $on_process_status = "";
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
                $on_process_status = $interdept_time[0]['interdept_status'] . " - " . $interdept_time[0]['department'];
            }else{
                $interdept_time[0]['final_progress_time'] = "00:00:00";
                // $row['sent_interdept_time'] = "00:00:00";
                $total_time = $row['final_progressed_timer'];
                $on_process_status = $row['status'];
            }


            if($row['approved_time'] == ""){
                $row['approved_time'] = "0000-00-00 00:00:00";
            }

            if($interdept_time[0]['final_progress_time'] == ""){
                $interdept_time[0]['final_progress_time'] = "00:00:00";
            }

            
            // if($row['sent_interdept_time'] == NULL){
            //     $row['sent_interdept_time'] = "00:00:00";
            //     // $sdn_processed_val = $row['final_progressed_timer'];
            // }

            $sdn_processed_value = "";
            if($row['sent_interdept_time'] == ""){
                $row['sent_interdept_time'] = "00:00:00";
                $sdn_processed_value = $row['final_progressed_timer'];
            }else{
                $sdn_processed_value =  $row['sent_interdept_time'];
            }

            $stopwatch = "00:00:00";
            if($row['sent_interdept_time'] == "00:00:00"){
                if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
                    $stopwatch  = $_SESSION['running_timer'];
                }
            }else{
                $stopwatch  = $row['sent_interdept_time'];
            }

            if($row['sent_interdept_time'] == "00:00:00"){
                $sdn_processed_val = $row['final_progressed_timer'];
            }

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
                        <label> " . $row['patlast'] . " , " . $row['patfirst'] . "  " . $row['patmiddle'] . "</label>
                        <input class='sensitive-hpercode' type='hidden' name='sensitive-hpercode' value= '" . $row['hpercode'] . "'>
                    </div>
                ";
            }
            
                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                        <td id="dt-patname">' . $pat_full_name  . '</td>
                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                        <td id="dt-phone-no">
                            <div class="">
                                <p> Referred: ' . $row['referred_by'] . '  </p>
                                <p> Landline: ' . $row['landline_no'] . ' </p>
                                <p> Mobile: ' . $row['mobile_no'] . ' </p>
                            </div>
                        </td>
                        <td id="dt-turnaround"> 
                            <i id="accordion-id- '.$accord_index.'" class="accordion-btn fa-solid fa-plus"></i>

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
                                <p class="pat-status-incoming">' . $on_process_status . '</p>';
                                if ($row['sensitive_case'] === 'true') {
                                    echo '<i class="pencil-btn fa-solid fa-pencil" style="pointer-events:none; opacity:0.3; color:#cc9900;"></i>';
                                }else{
                                    echo'<i class="pencil-btn fa-solid fa-pencil" style="color:#cc9900"></i>';
                                }
                                
                                echo '<input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                            </div>
                        </td>
                    </tr>';

            
            $previous = $row['reference_num'];
            $loop += 1;
            $accord_index += 1;
        }
        
        foreach ($data as $row) {
            if(isset($_POST['hpercode_arr'])){
                if(in_array($row['hpercode'], $_SESSION['fifo_hpercode']) && $row['status'] != 'Approved'){
                    continue;
                }
            }

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
            
            $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row['hpercode']]);
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
                // $row['sent_interdept_time'] = "00:00:00";
                $total_time = $row['final_progressed_timer'];
            }


            if($row['approved_time'] == ""){
                $row['approved_time'] = "0000-00-00 00:00:00";
            }

            if($row['deferred_time'] === "" || $row['deferred_time'] === null){
                $row['deferred_time'] = "0000-00-00 00:00:00";
            }

            if($interdept_time[0]['final_progress_time'] == ""){
                $interdept_time[0]['final_progress_time'] = "00:00:00";
            }

            $sdn_processed_value = "";
            if($row['sent_interdept_time'] == ""){
                $row['sent_interdept_time'] = "00:00:00";
                $sdn_processed_value = $row['final_progressed_timer'];
            }else{
                $sdn_processed_value =  $row['sent_interdept_time'];
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
                $pat_full_name = $row['patlast'] . ", " . $row['patfirst'] . " " . $row['patmiddle'];
            }

                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                        <td id="dt-patname">' . $pat_full_name  . '</td>
                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                        <td id="dt-phone-no">
                            <div class="">
                                <p> Referred: ' . $row['referred_by'] . '  </p>
                                <p> Landline: ' . $row['landline_no'] . ' </p>
                                <p> Mobile: ' . $row['mobile_no'] . ' </p>
                            </div>
                        </td>
                        <td id="dt-turnaround"> 
                            <i id="accordion-id- '.$accord_index.'" class="accordion-btn fa-solid fa-plus"></i>

                            <p class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </p>
                            <p class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</p>
                            <p class="sdn-proc-time-lbl"> SDN Processed: '. $sdn_processed_value .'</p>
                            
                            <div class="breakdown-div">
                                <p class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</p>
                                <p class="processed-time-lbl"> Total Processed: '.$total_time.'  </p>  
                                <p> Approval: '.$row['approved_time'] .'  </p>  
                                <p> Deferral: '.  $row['deferred_time'] .'  </p>  
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
            $accord_index += 1;
        }
    }
    else{
        try{
            $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
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
                    $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."'";
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
                }


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
    }


?>