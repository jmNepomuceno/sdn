<?php 
    include("../database/connection2.php");

    $search_lname = $_POST['search_lname'];
    $search_fname = $_POST['search_fname'];
    $search_mname = $_POST['search_mname'];


    // echo $search_lname;
    // echo $search_fname;
    // echo $search_mname;

    // echo $search_mname == "asdf";'
    $sql = "none";
    if($search_fname == "" && $search_mname == ""  && $search_lname != ""){   
        $sql = "SELECT * FROM hperson WHERE patlast LIKE '%$search_lname%' ";
    }
    else if($search_lname == "" && $search_mname == ""  && $search_fname != ""){
        $sql = "SELECT * FROM hperson WHERE patfirst LIKE '%$search_fname%' ";
    }
    else if($search_fname == "" && $search_lname == "" && $search_mname != ""){
        $sql = "SELECT * FROM hperson WHERE patmiddle LIKE '%$search_mname%' ";
    }

    //

    //FETCH THE WHOLE ROW
    // $sql = 'SELECT hpatcode, patlast, patfirst, patmiddle, patbdate FROM hperson WHERE patlast="'. $search_lname .'" && patfirst="'. $search_fname .'" && patmiddle="'. $search_mname .'"';
    if($sql != "none"){
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        //$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
            // echo $data['hpatcode'] , ' ' , $data['patlast'] , ' ' , $data['patfirst'] , ' ' , $data['patmiddle'] , ' ' , $data['patlast'] , ' ' , $data['patbdate'];
            
            echo '{"patient_code" :' , '"' , $data["hpatcode"] ,'"' , ',' , 
                '"pat_last_name" :' , '"' , $data["patlast"] ,'"' , ',' , 
                '"pat_first_name" :' , '"' ,$data["patfirst"] ,'"' , ',' , 
                '"pat_middle_name" :' , '"' ,$data["patmiddle"] ,'"' , ',' , 
                '"pat_middle_name" :' , '"' ,$data["patmiddle"] ,'"' , ',' , 
                '"pat_suffix_name" :' , '"' ,$data["patsuffix"] ,'"' , ',' , 
                '"pat_age" :' , '"' ,$data["pat_age"] ,'"' , ',' , 

                '"hpercode" :' , '"' ,$data["hpercode"] ,'"' , ',' ,  
                '"patsex" :' , '"' ,$data["patsex"] ,'"' , ',' , 
                '"patcstat" :' , '"' ,$data["patcstat"] ,'"' , ',' , 
                '"relcode" :' , '"' ,$data["relcode"] ,'"' , ',' , 
                '"natcode" :' , '"' ,$data["natcode"] ,'"' , ',' , 
                '"pat_occupation" :' , '"' ,$data["pat_occupation"] ,'"' , ',' , 
                '"hospital_code" :' , '"' ,$data["hospital_code"] ,'"' , ',' , 
                '"pat_passport_no" :' , '"' ,$data["pat_passport_no"] ,'"' , ',' , 
                '"phicnum" :' , '"' ,$data["phicnum"] ,'"' , ',' , 

                
                '"pat_bldg" :' , '"' ,$data["pat_bldg"] ,'"' , ',' , 
                '"pat_street_block" :' , '"' ,$data["pat_street_block"] ,'"' , ',' , 
                '"pat_email" :' , '"' ,$data["pat_email"] ,'"' , ',' , 
                '"pat_homephone_no" :' , '"' ,$data["pat_homephone_no"] ,'"' , ',' , 
                '"pat_region" :' , '"' ,$data["pat_region"] ,'"' , ',' , 
                '"pat_province" :' , '"' ,$data["pat_province"] ,'"' , ',' , 
                '"pat_municipality" :' , '"' ,$data["pat_municipality"] ,'"' , ',' , 
                '"pat_barangay" :' , '"' ,$data["pat_barangay"] ,'"' , ',' , 
                '"pat_mobile_no" :' , '"' ,$data["pat_mobile_no"] ,'"' , ',' , 
                
                '"pat_work_region" :' , '"' ,$data["pat_work_region"] ,'"' , ',' , 
                '"pat_work_province" :' , '"' ,$data["pat_work_province"] ,'"' , ',' , 
                '"pat_work_municipality" :' , '"' ,$data["pat_work_municipality"] ,'"' , ',' , 

                '"pat_bdate" :' , '"' ,$data["patbdate"] ,'"' 
                ,'} &';
        } 
    }else{
        echo "No User Found";
    }
    




    // echo '<pre>'; print_r($data); echo '</pre>';

    // $user_OTP = $data[0]['hospital_OTP'];

    // echo $user_OTP;
    // echo $otp_number;

    // if($user_OTP == $otp_number){
    //     //update the row with verified = TRUE
    //     $sql = "UPDATE sdn_hospital SET hospital_isVerified = :verify WHERE hospital_code=:hospital_code";

    //     $stmt = $pdo->prepare($sql);

    //     $stmt->bindParam(':hospital_code', $hospital_code, PDO::PARAM_INT);
    //     $stmt->bindParam(':verify', $verify, PDO::PARAM_BOOL);

    //     if ($stmt->execute()) {
    //         echo 'verified';
    //     }
    // }else{
    //     echo "not verified";
    // }
?>