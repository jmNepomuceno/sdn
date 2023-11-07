<?php 

    session_start();
    include('../database/connection2.php');

    // echo $_POST["timer_running"];

    // if($_POST["timer_running"] === true){
    //     echo "motherucker";
    //     // $temp_array = array();
    //     // for($i=0; $i< count($_SESSION["process_timer"]); $i++){
    //     //     $temp_array[] =  '{"pat_clicked_code" :  "' .$_SESSION["process_timer"][$i]["pat_clicked_code"].'" ,  elapsedTime: "' .$_SESSION["process_timer"][$i]["elapsedTime"].'"}';
    //     // }  

    //     // echo $temp_array;

    //     // $_SESSION["process_timer"] = json_encode($_SESSION["process_timer"]);
    //     // echo $_SESSION["process_timer"];
    // }else{
        
    // }

    $pat_clicked_code = $_POST['pat_clicked_code'];
    $elapsedTime = $_POST['elapsedTime'];
    $table_index = $_POST['table_index'];

    // echo $table_index; 
    $already = false;
    $index = 0;

    for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        if($pat_clicked_code == $_SESSION["process_timer"][$i]['pat_clicked_code']){
            $already = true;
            $index = $i;
            break;
        }
    } 

    //echo $already . " / " . $index .  " \n";

    if($already === true){
        // echo "true \n"; 
        $_SESSION["process_timer"][$index]['elapsedTime'] = $elapsedTime;
    }else{
        //echo "false \n";
        
        $_SESSION["process_timer"][] = array( 
            'pat_clicked_code' => $pat_clicked_code, 
            'elapsedTime' => $elapsedTime,
            'table_index' => $table_index
        );

        $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $pat_clicked_code ."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
    }

    // for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
    //     echo $_SESSION["process_timer"][$i]['pat_clicked_code'] . " ";
    //     echo $_SESSION["process_timer"][$i]['elapsedTime'] . " ";
    //     echo $_SESSION["process_timer"][$i]['table_index'];
    // }

    $temp = json_encode($_SESSION["process_timer"]);
    echo $temp;

?>