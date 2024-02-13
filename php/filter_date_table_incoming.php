<?php 
    session_start();
    include("../database/connection2.php");

     $ER_primary  = 0;
     $ER_secondary  = 0;
     $ER_tertiary  = 0;

     $OB_primary  = 0;
     $OB_secondary  = 0;
     $OB_tertiary  = 0;
     
     $OPD_primary  = 0;
     $OPD_secondary  = 0;
     $OPD_tertiary  = 0;


     $dateTime = new DateTime($_POST['from_date']);
     // Format the DateTime object to get the year, month, and day
     $formattedDate = $dateTime->format('Y-m-d') . '%';
     // echo $formattedDate;

     $sql = "SELECT pat_class, type, referred_by FROM incoming_referrals WHERE status='Approved' AND approved_time LIKE :proc_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':proc_date', $formattedDate, PDO::PARAM_STR);
     $stmt->execute();
     $tr_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
     // echo '<pre>'; print_r($tr_data); echo '</pre>';

     for($i = 0; $i < count($tr_data); $i++){
         echo '<input type="hidden" class="referred-by-class" value="' . $tr_data[$i]["referred_by"] . '">';
     }

     $in_table = [];
     
     foreach ($tr_data as $row){
         if (!in_array($row['referred_by'], $in_table)) {
             $in_table[] = $row['referred_by'];
         }   
     }

     for($i = 0; $i < count($in_table); $i++){
         foreach ($tr_data as $row){
             if($in_table[$i] === $row['referred_by']){
                 $referred_by = $row['referred_by'];

                 if($row['type'] === 'ER'){
                     if($row['pat_class'] === 'Tertiary'){
                         $ER_tertiary += 1;
                     }else if($row['pat_class'] === 'Secondary'){
                         $ER_secondary += 1;
                     }else if($row['pat_class'] === 'Primary'){
                         $ER_primary += 1;
                     }
                 }

                 else if($row['type'] === 'OB'){
                     if($row['pat_class'] === 'Tertiary'){
                         $OB_tertiary += 1;
                     }else if($row['pat_class'] === 'Secondary'){
                         $OB_secondary += 1;
                     }else if($row['pat_class'] === 'Primary'){
                         $OB_primary += 1;
                     }
                 }

                 else if($row['type'] === 'OPD'){
                     if($row['pat_class'] === 'Tertiary'){
                         $OPD_tertiary += 1;
                     }else if($row['pat_class'] === 'Secondary'){
                         $OPD_secondary += 1;
                     }else if($row['pat_class'] === 'Primary'){
                         $OPD_primary += 1;
                     }
                 }  
             }        
         }

         echo '
         <tr class="tr-div text-center"> 
             <td class="border-2 border-slate-700 col-span-3">'.$referred_by.'</td>
             <!-- ER -->
             <td class="add border-2 border-slate-700">'. $ER_primary .'</td>
             <td class="add border-2 border-slate-700">'. $ER_secondary .'</td>
             <td class="add border-2 border-slate-700">'. $ER_tertiary .'</td>

             <!-- OB -->
             <td class="add border-2 border-slate-700">'. $OB_primary .'</td>
             <td class="add border-2 border-slate-700">'. $OB_secondary .'</td>
             <td class="add border-2 border-slate-700">'. $OB_tertiary .'</td>

             <!-- OPD -->
             <td class="add border-2 border-slate-700">'. $OPD_primary .'</td>
             <td class="add border-2 border-slate-700">'. $OPD_secondary .'</td>
             <td class="add border-2 border-slate-700">'. $OPD_tertiary .'</td>

             <td class="sumCell border-2 border-slate-700">'. $row['referred_by'] .'</td>
         </tr>

     ';
     
         $ER_primary  = 0;
         $ER_secondary  = 0;
         $ER_tertiary  = 0;

         $OB_primary  = 0;
         $OB_secondary  = 0;
         $OB_tertiary  = 0;
         
         $OPD_primary  = 0;
         $OPD_secondary  = 0;
         $OPD_tertiary  = 0;
     }   
?>