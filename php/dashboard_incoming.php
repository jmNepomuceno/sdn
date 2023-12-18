<?php 
    session_start();
    include('../database/connection2.php');
    

    $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE (status='Approved' OR status='Checked' OR status='Arrived' OR status='Approved') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo $data['COUNT(*)'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    

</head>
<body class="h-screen overflow-hidden">
    <input type="hidden" id="total-processed-refer-inp" value=<?php echo $data['COUNT(*)'] ?>>
    
    <header class="header-div w-full h-[50px] flex flex-row justify-between items-center bg-[#1f292e]">
        <div class="w-[30%] h-full flex flex-row justify-start items-center">
            <div id="side-bar-mobile-btn" class="side-bar-mobile-btn w-[10%] h-full flex flex-row justify-center items-center cursor-pointer">
                <i class="fa-solid fa-bars text-white text-4xl"></i>
            </div>
            <h1 id="sdn-title-h1" class="text-white text-2xl ml-2 cursor-pointer"> Service Delivery Network</h1>
        </div>
        <div class="account-header-div w-[35%] h-full flex flex-row justify-end items-center mr-2">

            <div class="w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                    <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                </div> -->
                
                    <div id="notif-div" class="w-[20px] h-full flex flex-col justify-center items-center cursor-pointer">
                        <h1 id="notif-circle" class="absolute top-2 text-center w-[17px] h-[17px] rounded-full bg-red-600 ml-5 text-white text-xs "><span id="notif-span"></span></h1>
                        <i class="fa-solid fa-bell text-white text-xl"></i>
                        <audio id="notif-sound" preload='auto' muted loop>
                            <source src="../assets/sound/water_droplet.mp3" type="audio/mpeg">
                        </audio>
                    </div>

                    <div class="w-[20px] h-full flex flex-col justify-center items-center">
                        <i class="fa-solid fa-caret-down text-white text-xs mt-2"></i>
                    </div>
                
            </div>

            <div id="nav-account-div" class="header-username-div w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                <div class="w-[15%] h-full flex flex-row justify-end items-center mr-1">
                    <i class="fa-solid fa-user text-white text-xl"></i>
                </div>
                <div id="" class="w-auto h-full whitespace-nowrap flex flex-col justify-center items-center cursor-pointer">
                    <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> -->
                    <h1 class="text-white text-lg hidden sm:block"><?php echo $_SESSION['user_name'] ?> |   <?php echo $_SESSION['last_name'] ?>  <?php echo $_SESSION['first_name']  ?> <?php echo $_SESSION['middle_name']  ?>
                        
                    </h1>
                    
                </div>
                <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                    <i class="fa-solid fa-caret-down text-white text-xs"></i>
                </div>
            </div>
        </div>
    </header>  

    <div id="nav-mobile-account-div" class="sm:hidden flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-64 h-full overflow-y-auto transition-transform duration-300 transform translate-x-96 z-10">
        <div id="close-nav-mobile-btn" class="w-full h-[50px] mt-2 flex flex-row justify-start items-center">
            <i class="fa-solid fa-x ml-2 text-2xl"></i>
        </div>
        <div class="w-full h-[350px] flex flex-col justify-around items-center">
            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="" >Dashboard (Incoming)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Dashboard (Outgoing)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Dashboard (ER/OPD)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Statistics</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Settings</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Help</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Logout</h2>
            </div>
        </div>
    </div>

    <div id="nav-drop-account-div" class="hidden z-10 absolute right-0 top-[45px] flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-[15%] h-[400px]">
        <div class="w-full h-[350px] flex flex-col justify-around items-center">
            <?php if($_SESSION["user_name"] == "admin") {?>
                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                    <h2 id="admin-module-id" class="">Admin</h2>
                </div>
            <?php } ?>
            <div id="dashboard-incoming-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (Incoming)</h2>
            </div>

            <div id="dashboard-outgoing-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (Outgoing)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (ER/OPD)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Statistics</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Settings</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Help</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 id='logout-btn' class="">Logout</h2>
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-start items-center w-full h-full"> 

        

        <div class="w-full h-full border flex flex-col justify-start items-start overflow-hidden">
            <div class="w-full h-[5%] ml-[3%]  flex flex-row justify-start items-center border-t-0  rounded-md">
                <label class="text-5xl font-serif text-[#333333] mt-4">Dashboard For Incoming Referrals</label>
            </div>

            <div class="w-[40%] h-[15%] ml-[3%] mt-[1%] flex flex-col justify-start items-start">
                <label class="text-xl mt-2 mb-4">Filter</label>

                <div class="flex flex-row">
                    <label>From <input type="date"class="w-[200px] border border-slate-700 rounded-md"> to <input type="date" class=" w-[200px] border border-slate-700 rounded-md"></label>
                    <button id="butbut" class="w-[50px] h-[25px] bg-green-600 rounded-md ml-[10px] mt-[1px]">Go</button>
                </div>

                <div class="flex flex-col mt-[15px]"> 
                    <label id="month" class="text-3xl font-semibold">OCTOBER 2023</label>
                    <label id="date" class="font-semibold">as of October 20, 2023 - 10:09AM</label>
                </div>
            </div>


            <div class="flex flex-row justify-evenly items-center  w-[93%] h-[10%] ml-[3%] mt-[1%]">

                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl" id="total-processed-refer">18</label>
                    <label>Total Processed Referrals</label>
                </div>

                
                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl">00:00:00</label>
                    <label>Average Reception Time</label>
                </div>

                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl">00:00:00</label>
                    <label>Average Approval Time</label>
                </div>

                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl">00:00:00</label>
                    <label>Average Total Time</label>
                </div>

                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl">00:00:00</label>
                    <label>Fastest Response Time</label>
                </div>

                <div class=" w-[10%] h-full flex flex-col justify-center items-center  ml-[2%]">
                    <label class="font-semibold text-3xl">00:00:00</label>
                    <label>Slowest Response Time</label>
                </div>
                

            </div>


            <div class="flex flex-row justify-between items-center w-[93%] h-[25%]  ml-[3%] mt-[3%] ">
            
                <div class="w-[20%] h-[110%] ml-[2%]  flex flex-col  justify-center">
                    <label class="ml-[5.5%] font-semibold text-xl">Case Category</label>
                    <canvas id="myPieChart" class="w-full  "></canvas>
                    
                </div>

                <div class="w-[20%] h-[98%] ml-[2%] mb-[1.5%]  flex flex-col ">
                    <label class="ml-[9.5%] font-semibold text-xl">Case Type</label>
                    <canvas id="myPieChart2" class="w-full  "></canvas>
                </div>


                <div class="w-[20%] h-[130%] ml-[2%] mt-[1.7%]  flex flex-col  ">
                    <label class="-ml-[1%] mt-[0.5%] absolute font-semibold text-xl">Referring Health Facility</label>
                    <canvas id="myPieChart3" class="w-full"></canvas>
                </div>
            </div>

            <div class="w-full h-[50%]  absolute mt-[35%]">
                <table id="tablet" class=" border-2 border-slate-700 w-full border-collapse">

                    <thead class="w-full">
                        <tr>
                            <th class="border-2 border-slate-700" rowspan="3">
                                <label>Referring Health Facility</label>
                            </th>

                            <th class="border-2 border-slate-700" colspan="3">
                                <label>ER</label>
                            </th>

                            <th class="border-2 border-slate-700" colspan="3">
                                <label>OB</label>
                            </th>

                            <th class="border-2 border-slate-700" colspan="3">
                                <label>OPD</label>
                            </th>

                            <th class="border-2 border-slate-700" rowspan="2">
                                <label>Total</label>
                            </th>
                        </tr>   
            
                        <tr>
                            <th class="border-2 border-slate-700">
                                <label>Primary</label>
                            </th>


                            <th class="border-2 border-slate-700">
                                <label>Secondary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Tertiary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Primary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Secondary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Tertiary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Primary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Secondary</label>
                            </th>

                            <th class="border-2 border-slate-700">
                                <label>Tertiary</label>
                            </th>
                        </tr> 
                    </thead> 
                
                    <tbody class="w-full">
                            <!-- <tr class="tr-div text-center">
                                <td class="border-2 border-slate-700 col-span-3">CENTRO MEDICO DE SANTISIMO ROSARIO</td>

                                <td class="add border-2 border-slate-700">10</td>
                                <td class="add border-2 border-slate-700">2</td>
                                <td class="add border-2 border-slate-700">2</td>

                                <td class="add border-2 border-slate-700">2</td>
                                <td class="add border-2 border-slate-700">3</td>
                                <td class="add border-2 border-slate-700">2</td>

                                <td class="add border-2 border-slate-700">45</td>
                                <td class="add border-2 border-slate-700">6</td>
                                <td class="add border-2 border-slate-700">2</td>
                                <td class="sumCell border-2 border-slate-700"></td>
                            </tr>              -->


                        <?php 
                            $sql = "SELECT DISTINCT referred_by FROM incoming_referrals WHERE (status='Approved' OR status='Checked' OR status='Arrived' OR status='Approved') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $refer = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            // echo '<pre>'; print_r($refer); echo '</pre>';

                            for($i = 0; $i < count($refer); $i++){
                                echo '<input type="hidden" class="referred-by-class" value="' . $refer[$i]["referred_by"] . '">';
                            }

                            $ER_primary  = 0;
                            $ER_secondary  = 0;
                            $ER_tertiary  = 0;

                            $OB_primary  = 0;
                            $OB_secondary  = 0;
                            $OB_tertiary  = 0;
                            
                            $OPD_primary  = 0;
                            $OPD_secondary  = 0;
                            $OPD_tertiary  = 0;

                            $sql = "SELECT pat_class, type, referred_by FROM incoming_referrals WHERE (status='Approved' OR status='Checked' OR status='Arrived') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $tr_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            // echo '<pre>'; print_r($tr_data); echo '</pre>';

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
                    </tbody>
            </table>


            
        </div>
        <!-- ADMIN MODULE -->

    </div>

    
</div>

<script type="text/javascript" src="../js/dashboard_incoming.js?v=<?php echo time(); ?>"></script>
</body>
</html>