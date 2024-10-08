<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ini_set('session.gc_probability', 0);
    session_start();
    include("../database/connection2.php");

    $notif_value = 0;
    if($_POST['from_where'] == 'bell'){
        $referTo = htmlspecialchars($_SESSION["hospital_name"]);
        $sql = "SELECT status, type, patfirst FROM incoming_referrals WHERE status='Pending' AND refer_to= :referTo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':referTo', $referTo, PDO::PARAM_STR);
        
        try {
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $finalJsonString = json_encode($data);
                echo $finalJsonString;
            } else {
                echo json_encode(["error" => "Error executing query."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    else if($_POST['from_where'] == 'incoming'){
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

                    $seconds = (int)$stopwatch;

                     // // Calculate hours, minutes, and seconds
                     $hours = floor($seconds / 3600);
                     $minutes = floor((int)($seconds / 60) % 60);
                     $seconds = $seconds % 60;

                     // // Format the time as HH:MM:SS
                     $stopwatch = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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
    
    }
    else if($_POST['from_where'] == 'outgoing'){
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
           $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND referred_by='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
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

                       $seconds = (int)$stopwatch;

                        // // Calculate hours, minutes, and seconds
                        $hours = floor($seconds / 3600);
                        $minutes = floor((int)($seconds / 60) % 60);
                        $seconds = $seconds % 60;

                        // // Format the time as HH:MM:SS
                        $stopwatch = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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
                           <label> " . $row['patlast'] . " , " . $row['patfirst'] . "  " . $row['patmiddle'] . "</label>
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
                               <label> Referred by: ' . $row['referred_by'] . '  </label>
                               <label> Landline: ' . $row['landline_no'] . ' </label>
                               <label> Mobile: ' . $row['mobile_no'] . ' </label>
                           </div>
                       </td>
                       <td id="dt-turnaround"> 
                           <i class="accordion-btn fa-solid fa-plus"></i>

                           <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                           <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                           <label class="sdn-proc-time-lbl"> SDN Processed: '. $row['sent_interdept_time'] .'</label>
                           
                           <div class="breakdown-div">
                               <label class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</label>
                               <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
                               <label> Approval: '.$row['approved_time'] .'  </label>  
                               <label> Deferral: 0000-00-00 00:00:00  </label>  
                               <label> Cancelled: 0000-00-00 00:00:00  </label>  
                               <label> Arrived: 0000-00-00 00:00:00  </label>  
                               <label> Checked: 0000-00-00 00:00:00  </label>  
                               <label> Admitted: 0000-00-00 00:00:00  </label>  
                               <label> Discharged: 0000-00-00 00:00:00  </label>  
                               <label> Follow up: 0000-00-00 00:00:00  </label>  
                               <label> Ref. Back: 0000-00-00 00:00:00  </label>  
                           </div>
                       </td>
                       <td id="dt-stopwatch">
                           <div id="stopwatch-sub-div">
                               Processing: <span class="stopwatch">'.$stopwatch.'</span>
                           </div>
                       </td>
                       
                       <td id="dt-status">
                           <div> 
                               <label class="pat-status-incoming">' . $row['status'] . '</label>';
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
    }else if($_POST['from_where'] == 'history_log'){
        try{
            $sql = "SELECT * FROM sdn_users JOIN history_log ON sdn_users.username = history_log.username WHERE sdn_users.hospital_code='" . $_SESSION["hospital_code"] . "' ORDER BY history_log.date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $temp_1 = "";
            $temp_2 = "";   
            $temp_3 = "";

            for($i = 0; $i < count($data); $i++){

                if($data[$i]['activity_type'] === 'user_login'){
                    $name = $data[$i]['user_lastname'] . ', ' . $data[$i]['user_firstname'] . ' ' . $data[$i]['user_middlename'] . '. ';
                    $originalDate = $data[$i]['user_lastLoggedIn'];
                    $currentDate = date('Y-m-d H:i:s');
                    $formattedDate = "";

                    $dateTime = new DateTime($data[$i]['date']);
                    $formattedDate = $dateTime->format('F j, Y g:ia');

                    $temp_1 = $formattedDate;
                    $temp_2 = "Online Status: " . $data[$i]['action'];
                    $temp_3 = $name;
                }
                else {
                    $name = $data[$i]['user_lastname'] . ', ' . $data[$i]['user_firstname'] . ' ' . $data[$i]['user_middlename'] . '. ';
                    $originalDate = $data[$i]['date'];
                    $currentDate = date('Y-m-d H:i:s');
                    $formattedDate = "";

                    $dateTime = new DateTime($originalDate);
                    $formattedDate = $dateTime->format('F j, Y g:ia');

                    $temp_1 = $formattedDate;
                    $temp_2 = $data[$i]['action'] . ' ' . $data[$i]['pat_name'];
                    $temp_3 = $name;
                }
                
                $style_color = "#ffffff";
                $text_color = "#1f292e";
                if($i % 2 == 1){
                    $style_color = "#1f292e"; 
                    $text_color = "#ffffff";
                }

                echo '
                    <div class="history-div w-full h-[10%] border-b-2 border-[#bfbfbf] flex flex-row justify-between items-center bg-['.$style_color.'] text-['.$text_color.']">
                        <div class="w-[20%] h-full flex flex-row justify-around items-center ml-4">
                            <i class="fa-regular fa-calendar-days text-2xl "></i>
                            <h3>'. $temp_1 .'</h3>
                        </div>
        
                        <div class="w-[30%] h-full flex flex-row justify-around items-center">
                            <!-- <i class="fa-regular fa-calendar-days text-2xl "></i> -->
                            <h3 class="text-base"<span id="status-login">'. $temp_2 .'</span></h3>
                        </div>

                        <div class="w-[20%] h-full flex flex-row justify-evenly items-center mr-4">
                            <h3> '. $temp_3 .' </h3>
                            <i class="fa-solid fa-user text-2xl "></i>
        
                        </div>
                    </div>
                ';
            }
        }catch(PDOException $e){
            echo $notif_value;
        }
    }else if($_POST['from_where'] == 'incoming_interdept'){
        $sql = "SELECT * FROM incoming_interdept ORDER BY recept_time ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode($data);
        $index = 0;
        $previous = 0;
        $loop = 0;
        $i=0;
        // Loop through the data and generate table rows
        foreach ($data as $row) {
            if($previous == 0){
                $index += 1;
            }else{
                if($data[0]['reference_num'] == $previous){
                    $index += 1;
                }else{
                    $index = 1;
                }  
            }

            $sql = "SELECT reference_num, patlast, patfirst, patmiddle, status_interdept FROM incoming_referrals WHERE hpercode='". $row['hpercode'] ."' ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $stopwatch = "00:00:00";
            if($i == 0 && $_SESSION['running_timer'] != ""){
                $stopwatch = $_SESSION['running_timer'];
            }
            echo $stopwatch;
            // echo '<tr class="tr-incoming-interdept">
            //         <td id="dt-refer-no"> ' . $data[0]['reference_num'] . ' - '.$index.' </td>
            //         <td id="dt-patname">' . $data[0]['patlast'] , ", " , $data[0]['patfirst'] , " " , $data[0]['patmiddle']  . '</td>
            //         <td id="dt-turnaround"> 
            //             '.$row['recept_time'].'
            //         </td>
            //         <td id="dt-stopwatch">
            //             <div id="stopwatch-sub-div">
            //                 Processing: <span class="stopwatch">'.$stopwatch.'</span>
            //             </div>
            //         </td>
                    
            //         <td id="dt-status">
            //             <div> 
                            
            //                 <label class="pat-status-incoming">'.$data[0]['status_interdept'].'</label>
            //                 <i class="pencil-btn fa-solid fa-pencil"></i>
            //                 <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

            //             </div>
            //         </td>
            //     </tr>';

            $previous = $data[0]['reference_num'];
            $loop += 1;
            $i += 1;
        }

        // Close the database connection
        $pdo = null;
    }

    
?>

