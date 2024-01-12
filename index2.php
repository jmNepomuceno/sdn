<?php 
    include('database/connection2.php');
    session_start();

    $tm_fields = array("Last Name","First Name","Middle Name", "Birthday" ,"Mobile No." ,"Username" ,"Password" ,"Confirm Password");
    $tm_input_names = array("last_name","first_name","middle_name", "birthday" ,"mobile_no" ,"username" ,"password" ,"confirm_password");
    $tm_id = array("tms-last-name","tms-first-name","tms-middle-name", "tms-birthday" ,"tms-mobile-no" ,"tms-username" ,"tms-password" ,"tms-confirm-password");

    $sdn_fields = array("Hospital Name","Hospital Code","Address: Region","Address: Province", "Address: City/ Municipality" ,"Address: Barangay","Zip Code" ,"Email Address" ,
                "Hospital Landline No.","Hospital Mobile No.","Hospital Director","Hospital Director Mobile No.","Point Person","Point Person Mobile No.");
    $sdn_input_names = array("hospital_name","hospital_code","address_region","address_province", "address_municipality" ,"address_barangay","zip_code" ,"email_address" ,
                    "landline_no" ,"hospital_mobile_no", "hospital_director", "hospital_director_mobile_no","point_person","point_person_mobile_no");
    
    $sdn_id = array("sdn-hospital-name","sdn-hospital-code","sdn-address-region","sdn-address-province", "sdn-address-municipality" ,"sdn-address-barangay","sdn-zip-code" ,"sdn-email-address" ,
                    "sdn-landline-no" ,"sdn-hospital-mobile-no", "sdn-hospital-director", "sdn-hospital-director-mobile-no","sdn-point-person","sdn-point-person-mobile-no");

    //authorization
    $sdn_autho_fields = array("Hospital Code", "Cipher Key" , "Last Name", "First Name", "Middle Name", "Extension Name", "Username" , "Password", "Confirm Password");

    $sdn_autho_input_names = array("hospital_code", "cipher_key" , "last_name", "first_name", "middle_name", "extension_name", "username" , "password", "confirm_password");
    
    $sdn_autho_id = array("sdn-auth-hospital-code", "sdn-cipher-key" , "sdn-last-name", "sdn-first-name", "sdn-middle-name", "sdn-extension-name", "sdn-username" , "sdn-password", "sdn-confirm-password");

    if($_POST){
        $_SESSION["process_timer"] = [] ;
         
        $sdn_username = $_POST['sdn_username'];
        $sdn_password = $_POST['sdn_password'];
        $account_validity = false;
        // //query to check if the user is already logged in.
        // if($sdn_username != "" && $sdn_password != ""){
        //     $_SESSION['user_name'] = "John Marvin Nepomuceno";
        //     $_SESSION['user_password'] = "password";
        //     header('Location: ./main.php');
        // }

        // login verifaction for the outside users
        if($sdn_username != "admin" && $sdn_password != "admin"){
            try{
                $stmt = $pdo->prepare('SELECT * FROM sdn_users WHERE username = ? AND password = ?');
                $stmt->execute([$sdn_username , $sdn_password]);
                $data_child = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // echo '<pre>'; print_r($data_child); echo '</pre>';

                if(count($data_child) == 1){
                    $account_validity = true;
                }

                // echo '<pre>'; print_r($data_child); echo '</pre>';
                // echo $data_child[0]['hospital_code'];


                // $stmt_all_data = $pdo->prepare("SELECT sdn_hospital.*
                //                                 FROM sdn_hospital
                //                                 JOIN sdn_users ON sdn_hospital.hospital_code = sdn_users.hospital_code
                //                                 WHERE sdn_users.hospital_code = 6574");

                // $stmt_all_data->execute();
                // $data_all_data = $stmt_all_data->fetchAll(PDO::FETCH_ASSOC);
                
                if($account_validity == true){
                    $stmt = $pdo->prepare('SELECT * FROM sdn_hospital WHERE hospital_code = ?');
                    $stmt->execute([$data_child[0]['hospital_code']]);
                    $data_parent = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // echo '<pre>'; print_r($data_parent); echo '</pre>';

                    $_SESSION['hospital_code'] = $data_parent[0]['hospital_code'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];
                    $_SESSION['hospital_email'] = $data_parent[0]['hospital_email'];
                    $_SESSION['hospital_landline'] = $data_parent[0]['hospital_landline'];
                    $_SESSION['hospital_mobile'] = $data_parent[0]['hospital_mobile'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];

                    $_SESSION['user_name'] = $data_child[0]['username'];
                    $_SESSION['user_password'] = $data_child[0]['password'];
                    $_SESSION['first_name'] = $data_child[0]['user_firstname'];
                    $_SESSION['last_name'] = $data_child[0]['user_lastname'];
                    $_SESSION['middle_name'] = $data_child[0]['user_middlename'];
                    $_SESSION['user_type'] = 'outside';

                    $_SESSION['post_value_reload'] = 'false';

                    // Get the current date and time
                    $timezone = new DateTimeZone('Asia/Manila'); // Replace 'Your/Timezone' with your actual time zone
                    $currentDateTime = new DateTime("",$timezone);

                    // Format date components
                    $year = $currentDateTime->format('Y');
                    $month = $currentDateTime->format('m');
                    $day = $currentDateTime->format('d');

                    $hours = $currentDateTime->format('H');
                    $minutes = $currentDateTime->format('i');
                    $seconds = $currentDateTime->format('s');

                    $final_date = $year . "/" . $month . "/" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
                    $normal_date = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;

                    $_SESSION['login_time'] = $final_date;

                    $sql = "UPDATE incoming_referrals SET login_time = '". $final_date ."' , login_user='". $sdn_username ."' ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username=:username AND password=:password";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':username', $data_child[0]['username'], PDO::PARAM_STR);
                    $stmt->bindParam(':password', $data_child[0]['password'], PDO::PARAM_STR);
                    $stmt->execute();

                    // for history log
                    $act_type = 'user_login';
                    $pat_name = " ";
                    $hpercode = " ";
                    $action = 'online';
                    $user_name = $data_child[0]['username'];
                    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);

                    $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
                    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
                    $stmt->bindParam(3, $normal_date, PDO::PARAM_STR);
                    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
                    $stmt->bindParam(5, $action, PDO::PARAM_STR);
                    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
                    $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

                    $stmt->execute();

                    header('Location: ./main.php');
                }else{
                    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script type="text/javascript">
                                var jQuery = $.noConflict(true);
                                jQuery(document).ready(function() {
                                    jQuery("#modal-title").text("Warning")
                                    jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                                    jQuery("#modal-icon").removeClass("fa-circle-check")
                                    jQuery("#modal-body").text("Invalid username and password!")
                                    jQuery("#ok-modal-btn").text("Close")
                                    jQuery("#myModal").modal("show");
                                });
                            </script>';
                }
                
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }
        //verification for admin user logged in
        else if($sdn_username == "admin" && $sdn_password == "admin"){
            // $_SESSION['user_name'] = "Bataan General Hospital and Medical Center";
            $_SESSION['hospital_code'] = '1437';
            $_SESSION['hospital_name'] = "Bataan General Hospital and Medical Center";
            $_SESSION['hospital_landline'] = '333-3333';
            $_SESSION['hospital_mobile'] = '3333-3333-333';
            // $_SESSION['user_name'] = "Administrator";
            // $_SESSION['user_password'] = $sdn_password;

            $_SESSION['user_name'] = 'admin';
            $_SESSION['user_password'] = 'admin';
            $_SESSION['last_name'] = 'Administrator';
            $_SESSION['first_name'] = '';
            $_SESSION['middle_name'] = '';
            $_SESSION['user_type'] = 'admin';
            // $_SESSION["process_timer"] = [];
            $_SESSION['post_value_reload'] = 'false';

            // Get the current date and time
            $timezone = new DateTimeZone('Asia/Manila'); // Replace 'Your/Timezone' with your actual time zone
            $currentDateTime = new DateTime("",$timezone);

            // Format date components
            $year = $currentDateTime->format('Y');
            $month = $currentDateTime->format('m');
            $day = $currentDateTime->format('d');

            $hours = $currentDateTime->format('H');
            $minutes = $currentDateTime->format('i');
            $seconds = $currentDateTime->format('s');

            $final_date = $year . "/" . $month . "/" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
            $temp_date = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
            
            $_SESSION['login_time'] = $final_date;

            $sql = "UPDATE incoming_referrals SET login_time = :final_date, login_user = :sdn_username";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':final_date', $final_date, PDO::PARAM_STR);
            $stmt->bindParam(':sdn_username', $sdn_username, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username='admin' AND password='admin'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // for history log
            $act_type = 'user_login';
            $pat_name = " ";
            $hpercode = " ";
            $action = 'online';
            $user_name = 'admin';
            $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
            $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
            $stmt->bindParam(3, $temp_date, PDO::PARAM_STR);
            $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
            $stmt->bindParam(5, $action, PDO::PARAM_STR);
            $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
            $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

            $stmt->execute();

            header('Location: ./main.php');
        } 

        else if($sdn_username != 'admin' || $sdn_password != 'admin'){
            echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script type="text/javascript">
                        var jQuery = $.noConflict(true);
                        jQuery(document).ready(function() {
                            jQuery("#modal-title").text("Warning")
                            jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                            jQuery("#modal-icon").removeClass("fa-circle-check")
                            jQuery("#modal-body").text("Invalid username and password!")
                            jQuery("#ok-modal-btn").text("Close")
                            jQuery("#myModal").modal("show");
                        });
                    </script>';
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
    <link rel="stylesheet" href="index2.css">
</head>
<body>
        <!-- aesthetic hospital website background -->
    <div class="container">
        <div class="coating-div">
            <img src="./assets/login_imgs/main_bg3.jpg" alt="main_bg-image" class="blurred-image">
            <div></div>
        </div>

        <div class="main-content">
            <h1 class="letter-border">Service Delivery Network</h1>

            <div class="glass-div">
                <h1 id="login-txt">Login</h1>
                <form>
                    <div id="username-div">
                        <i class="username-icon fa-solid fa-user"></i>
                        <input type="text" id="username-inp" placeholder="Username">
                    </div>

                    <div id="password-div">
                        <i class="username-icon fa-solid fa-user"></i>
                        <input type="password" id="password-inp" placeholder="Password">
                    </div>

                    <button id="login-btn">Login</button>
                </form>
                
                <div class="query-signin-div">
                    <label for="" id="query-signin-txt">Don't have an account yet? Sign in</label>
                </div>
            </div>
        </div>

        <div class="sub-content">
            <div class="sub-content-header">SERVICE DELIVERY NETWORK</div>

            <div class="sub-nav-btns">
                <button type="button" id="registration-btn" class="btn btn-primary">Registration</button>
                <button type="button" id="authorization-btn" class="btn btn-dark">Authorization</button>
            </div>

            <div class="sub-content-note">
                This is one-time registration ONLY. If you already have an account, no need to register again.
                <span style="color:red; margin-left:6%;">A one-time password and authorization key will be send to your registered mobile no.</span>
            </div>

            <form class="sub-content-registration-form">
                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Name</label>
                    <input type="text" class="reg-inputs">
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <div class="reg-form-divs">
                    
                </div>

                <button id="register-confirm-btn">Register</button>
            </form>
        </div>


    </div>
            
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    

    <script src="./index2.js?v=<?php echo time(); ?>"></script>
</body>
</html>