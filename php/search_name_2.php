<?php
    include("../database/connection2.php");

    $search_lname = $_POST['search_lname'];
    $search_fname = $_POST['search_fname'];
    $search_mname = $_POST['search_mname'];

    $sql = "none";
    $search_lname = filter_input(INPUT_POST, 'search_lname');
    $search_fname = filter_input(INPUT_POST, 'search_fname');
    $search_mname = filter_input(INPUT_POST, 'search_mname');



    $conditions = array();

    if (!empty($search_lname)) {
        $conditions[] = "patlast LIKE :search_lname";
    }

    if (!empty($search_fname)) {
        $conditions[] = "patfirst LIKE :search_fname";
    }

    if (!empty($search_mname)) {
        $conditions[] = "patmiddle LIKE :search_mname";
    }

    $sql = "SELECT patfirst, patlast, patmiddle, hpercode, patbdate, status FROM hperson";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($sql);

    if (!empty($search_lname)) {
        $search_lname_param = "%$search_lname%";
        $stmt->bindParam(':search_lname', $search_lname_param, PDO::PARAM_STR);
    }

    if (!empty($search_fname)) {
        $search_fname_param = "%$search_fname%";
        $stmt->bindParam(':search_fname', $search_fname_param, PDO::PARAM_STR);
    }

    if (!empty($search_mname)) {
        $search_mname_param = "%$search_mname%";
        $stmt->bindParam(':search_mname', $search_mname_param, PDO::PARAM_STR);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $finalJsonString = json_encode($data);
    // echo $finalJsonString;

    if(count($data) >= 1){
        for($i = 0; $i < count($data); $i++){
            if ($i % 2 == 0) {
                $bg_color = "#e6e6e6";
            } else {
                $bg_color = "#ffffff";
            }

            echo '<div class="search-sub-div w-full h-[80px] flex flex-col justify-center items-center border-b border-black bg-['. $bg_color.'] cursor-pointer hover:bg-[#85b2f9] text-sm">';
            echo   '<div class="w-full h-[40%] flex flex-row justify-between items-center">';
            echo       '<h1 class="ml-2">Patient ID: <span class="search-sub-code">'. $data[$i]['hpercode'] .'</span> </h1>';
            echo       '<div class="w-[25%] h-full flex flex-row justify-around items-center">';
            echo          '<h1>'. $data[$i]['patbdate'] .'</h1>';
            echo           '<span class="fa-solid fa-user"></span>';
            echo     ' </div>';
            echo ' </div>';
            echo ' <div class="w-full h-[40%] flex flex-row justify-between items-center">';
            echo     ' <h3 class="uppercase ml-2 font-bold underline">'. $data[$i]['patlast'] . ", " . $data[$i]['patfirst'] . " " . $data[$i]['patmiddle'] .'</h3>';
            echo      '<h3 class="mr-4">' . (isset($data[$i]['status']) ? "Status: Referred-" . $data[$i]['status'] : "Status: Not yet referred") . '</h3>';
            echo  '</div>';
            echo'</div>';
        }
    }else{
        echo "No User Found";
    }
?>