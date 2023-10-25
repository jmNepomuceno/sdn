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
    }else{
        $sql = 'SELECT * FROM hperson WHERE patlast="'. $search_lname .'" && patfirst="'. $search_fname .'" && patmiddle="'. $search_mname .'"';
    }

    //

    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    //FETCH THE WHOLE ROW
    // $sql = 'SELECT hpatcode, patlast, patfirst, patmiddle, patbdate FROM hperson WHERE patlast="'. $search_lname .'" && patfirst="'. $search_fname .'" && patmiddle="'. $search_mname .'"';
    if($sql != "none"){
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($data); echo '</pre>';

        while($data = $stmt->fetch(PDO::FETCH_ASSOC)){

            // if the query is slow, remove the region/province/city/brgy code and directly save the name of the regions/province/city/brgy.
            // FROM REGION CODE TO REGION DESCRIPTION QUERY
            // permanent address
            $sql_province = 'SELECT province_description FROM provinces WHERE province_code="'. $data["pat_province"] .'" ';
            $stmt_province = $pdo->prepare($sql_province);
            $stmt_province->execute();
            $data_province = $stmt_province->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_city = 'SELECT municipality_description FROM city WHERE municipality_code="'. $data["pat_municipality"] .'" ';
            $stmt_city = $pdo->prepare($sql_city);
            $stmt_city->execute();
            $data_city = $stmt_city->fetchAll(PDO::FETCH_ASSOC);

            $sql_brgy = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $data["pat_barangay"] .'" ';
            $stmt_brgy = $pdo->prepare($sql_brgy);
            $stmt_brgy->execute();
            $data_brgy = $stmt_brgy->fetchAll(PDO::FETCH_ASSOC);

            $data["pat_province"] = $data_province[0]['province_description'];
            $data["pat_municipality"] = $data_city[0]['municipality_description'];
            $data["pat_barangay"] = $data_brgy[0]['barangay_description'];

            // current address
            $sql_province_ca = 'SELECT province_description FROM provinces WHERE province_code="'. $data["pat_curr_province"] .'" ';
            $stmt_province_ca = $pdo->prepare($sql_province_ca);
            $stmt_province_ca->execute();
            $data_province_ca = $stmt_province_ca->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_city_ca = 'SELECT municipality_description FROM city WHERE municipality_code="'. $data["pat_curr_municipality"] .'" ';
            $stmt_city_ca = $pdo->prepare($sql_city_ca);
            $stmt_city_ca->execute();
            $data_city_ca = $stmt_city_ca->fetchAll(PDO::FETCH_ASSOC);

            $sql_brgy_ca = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $data["pat_curr_barangay"] .'" ';
            $stmt_brgy_ca = $pdo->prepare($sql_brgy_ca);
            $stmt_brgy_ca->execute();
            $data_brgy_ca = $stmt_brgy_ca->fetchAll(PDO::FETCH_ASSOC);

            $data["pat_curr_province"] = $data_province_ca[0]['province_description'];
            $data["pat_curr_municipality"] = $data_city_ca[0]['municipality_description'];
            $data["pat_curr_barangay"] = $data_brgy_ca[0]['barangay_description'];

            // current workplace address
            if($data["pat_work_province"] != "N/A"){
                $sql_province_cwa = 'SELECT province_description FROM provinces WHERE province_code="'. $data["pat_work_province"] .'" ';
                $stmt_province_cwa = $pdo->prepare($sql_province_cwa);
                $stmt_province_cwa->execute();
                $data_province_cwa = $stmt_province_cwa->fetchAll(PDO::FETCH_ASSOC);
                
                $sql_city_cwa = 'SELECT municipality_description FROM city WHERE municipality_code="'. $data["pat_work_municipality"] .'" ';
                $stmt_city_cwa = $pdo->prepare($sql_city_cwa);
                $stmt_city_cwa->execute();
                $data_city_cwa = $stmt_city_cwa->fetchAll(PDO::FETCH_ASSOC);

                $sql_brgy_cwa = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $data["pat_work_barangay"] .'" ';
                $stmt_brgy_cwa = $pdo->prepare($sql_brgy_cwa);
                $stmt_brgy_cwa->execute();
                $data_brgy_cwa = $stmt_brgy_cwa->fetchAll(PDO::FETCH_ASSOC);

                $data["pat_work_province"] = $data_province_cwa[0]['province_description'];
                $data["pat_work_municipality"] = $data_city_cwa[0]['municipality_description'];
                $data["pat_work_barangay"] = $data_brgy_cwa[0]['barangay_description'];
            }
            

            // WALA PA SA OFW NA QUERY GL :))

            // PERSONAL INFORMATION
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

                // PERMANENT ADDRESS
                '"pat_bldg" :' , '"' ,$data["pat_bldg"] ,'"' , ',' , 
                '"pat_street_block" :' , '"' ,$data["pat_street_block"] ,'"' , ',' , 
                '"pat_email" :' , '"' ,$data["pat_email"] ,'"' , ',' , 
                '"pat_homephone_no" :' , '"' ,$data["pat_homephone_no"] ,'"' , ',' , 
                '"pat_region" :' , '"' ,$data["pat_region"] ,'"' , ',' , 
                // '"pat_province" :' , '"' ,$data_province[0]['province_description'] ,'"' , ',' , 
                // '"pat_municipality" :' , '"' ,$data_city[0]['municipality_description'] ,'"' , ',' , 
                // '"pat_barangay" :' , '"' ,$data_brgy[0]['barangay_description'] ,'"' , ',' , 
                '"pat_province" :' , '"' ,$data['pat_province'] ,'"' , ',' , 
                '"pat_municipality" :' , '"' ,$data['pat_municipality'] ,'"' , ',' , 
                '"pat_barangay" :' , '"' ,$data['pat_barangay'] ,'"' , ',' , 
                '"pat_mobile_no" :' , '"' ,$data["pat_mobile_no"] ,'"' , ',' , 

                // CURRENT ADDRESS
                '"pat_curr_bldg" :' , '"' ,$data["pat_curr_bldg"] ,'"' , ',' , 
                '"pat_curr_street" :' , '"' ,$data["pat_curr_street"] ,'"' , ',' , 
                '"pat_curr_region" :' , '"' ,$data["pat_curr_region"] ,'"' , ',' , 
                // '"pat_curr_province" :' , '"' , $data_province_ca['province_description'] ,'"' , ',' , 
                // '"pat_curr_municipality" :' , '"' ,$data_city_ca['municipality_description'] ,'"' , ',' , 
                // '"pat_curr_barangay" :' , '"' ,$data_brgy_ca['barangay_description'] ,'"' , ',' , 

                '"pat_curr_province" :' , '"' , $data['pat_curr_province'] ,'"' , ',' , 
                '"pat_curr_municipality" :' , '"' ,$data['pat_curr_municipality'] ,'"' , ',' , 
                '"pat_curr_barangay" :' , '"' ,$data['pat_curr_barangay'] ,'"' , ',' , 
                '"pat_curr_homephone_no" :' , '"' ,$data["pat_curr_homephone_no"] ,'"' , ',' , 
                '"pat_curr_mobile_no" :' , '"' ,$data["pat_curr_mobile_no"] ,'"' , ',' , 
                '"pat_email_ca" :' , '"' ,$data["pat_email_ca"] ,'"' , ',' , 

                // CURRENT WORKPLACE ADDRESS
                '"pat_work_bldg" :' , '"' ,$data["pat_work_bldg"] ,'"' , ',' , 
                '"pat_work_street" :' , '"' ,$data["pat_work_street"] ,'"' , ',' , 
                '"pat_work_region" :' , '"' ,$data["pat_work_region"] ,'"' , ',' , 
                '"pat_work_province" :' , '"' ,$data["pat_work_province"] ,'"' , ',' , 
                '"pat_work_municipality" :' , '"' ,$data["pat_work_municipality"] ,'"' , ',' , 
                '"pat_work_barangay" :' , '"' ,$data["pat_work_barangay"] ,'"' , ',' , 
                '"pat_namework_place" :' , '"' ,$data["pat_namework_place"] ,'"' , ',' , 
                '"pat_work_landline_no" :' , '"' ,$data["pat_work_landline_no"] ,'"' , ',' , 
                '"pat_work_email_add" :' , '"' ,$data["pat_work_email_add"] ,'"' , ',' , 
                
                // OFW
                '"ofw_employers_name" :' , '"' ,$data["ofw_employers_name"] ,'"' , ',' , 
                '"ofw_occupation" :' , '"' ,$data["ofw_occupation"] ,'"' , ',' , 
                '"ofw_place_of_work" :' , '"' ,$data["ofw_place_of_work"] ,'"' , ',' , 
                '"ofw_bldg" :' , '"' ,$data["ofw_bldg"] ,'"' , ',' , 
                '"ofw_street" :' , '"' ,$data["ofw_street"] ,'"' , ',' , 
                '"ofw_region" :' , '"' ,$data["ofw_region"] ,'"' , ',' , 
                '"ofw_province" :' , '"' ,$data["ofw_province"] ,'"' , ',' , 
                '"ofw_municipality" :' , '"' ,$data["ofw_municipality"] ,'"' , ',' , 
                '"ofw_country" :' , '"' ,$data["ofw_country"] ,'"' , ',' , 
                '"ofw_office_phone_no" :' , '"' ,$data["ofw_office_phone_no"] ,'"' , ',' , 
                '"ofw_mobile_phone_no" :' , '"' ,$data["ofw_mobile_phone_no"] ,'"' , ',' , 

                '"pat_bdate" :' , '"' ,$data["patbdate"] ,'"' 
                ,'} &';
        } 
    }
    else{
        echo "No User Found";
    }
    

    // $sql = "INSERT INTO hperson (hpercode, hpatcode, patlast, patfirst, patmiddle, patsuffix, patbdate, pat_age, patsex, patcstat, natcode, pat_occupation, pat_passport_no, relcode, hospital_code, phicnum,
    // pat_bldg, pat_street_block, pat_region, pat_province, pat_municipality, pat_barangay, pat_email, pat_homephone_no, pat_mobile_no,
    // pat_curr_bldg , pat_curr_street, pat_curr_region, pat_curr_province , pat_curr_municipality, pat_curr_barangay, pat_email_ca, pat_curr_homephone_no, pat_curr_mobile_no, 
    // pat_work_bldg, pat_work_street, pat_work_region, pat_work_province, pat_work_municipality, pat_work_barangay,pat_namework_place, pat_work_landline_no, pat_work_email_add, 
    // ofw_employers_name, ofw_occupation, ofw_place_of_work, ofw_bldg, ofw_street, ofw_region, ofw_province, ofw_municipality, ofw_office_phone_no, ofw_mobile_phone_no)
    // VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,  ?,?,?,?,?,?,?,?,?,  ?,?,?,?,?,?,?,?,?,  ?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?)";


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