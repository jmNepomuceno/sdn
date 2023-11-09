<?php
    session_start();
    include('../database/connection2.php');
    // echo isset($_SESSION["process_timer"]);

    // echo count($_SESSION["process_timer"]);   
    $timer_running = false;

    $post_value_reload = '';

    if(count($_SESSION["process_timer"]) >= 1){
        // echo "here";
        // for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        //     foreach ($_SESSION["process_timer"][$i] as $key => $value) {
        //         echo "Key: $key, Value: $value<br>";
        //     }
        // }
        $timer_running = true;
    }

    $sql = "SELECT * FROM incoming_referrals WHERE progress_timer!=''";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) > 0){
        $_SESSION['post_value_reload'] = 'true';
        $post_value_reload = $_SESSION['post_value_reload'];
    }

    

    // echo $timer_running;
    // if(count($_SESSION["process_timer"]) >= 1){
    //     for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
    //         echo $_SESSION['process_timer'][$i]['elapsedTime'] . '<br/>';
    //     }
    // }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../output.css">

    <style>
        .dataTables_length {
        margin-top:0;
        margin-bottom:5px;
    }   
    </style>

</head>
<body>
    <!-- <button id="pending-stop-btn" class="border-2 border-black">Stop</button> -->
    <input id="timer-running-input" type="hidden" name="timer-running-input" value=<?php echo $timer_running ?>>
    <input id="post-value-reload-input" type="hidden" name="post-value-reload-input" value=<?php echo $post_value_reload ?>>
    

    <!-- <input id="timer-running-input" type="hidden" name="timer-running-input" value="false"> -->

    <div class="w-full h-full flex flex-col justify-start items-center bg-white">
        <div class="w-full h-[5%] flex flex-row justify-around items-center mt-4">

            <div class="w-[10%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Referral No.</label>
                <input id="incoming-referral-no-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md outline-none">
            </div>
            

            <div class="w-[12%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Last Name</label>
                <input id="incoming-last-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[10%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">First Name</label>
                <input id="incoming-first-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[12%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Middle Name</label>
                <input id="incoming-middle-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[6%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Case Type</label>
                <select id='incoming-type-select' class="w-full border-2 border-[#bfbfbf] rounded-md">
                    <option value=""> None</option>
                    <option value="ER"> ER</option>
                    <option value="OB"> OB</option>
                    <option value="OPD"> OPD</option>
                    <option value="PCR"> PCR</option>
                </select>
            </div>


            <div class="w-[15%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Agency</label>
                <select id='incoming-agency-select' class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%">
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


            <div class="w-[9%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Status</label>
                <select id='incoming-status-select' class="w-full border-2 border-[#bfbfbf] rounded-md">
                    <option value="Pending">Pending</option>
                    <option value="All"> All</option>
                    <option value="On Process"> On Process</option>
                    <option value="Deferred"> Deferred</option>
                    <option value="Aproved"> Aproved</option>
                    <option value="Cancelled"> Cancelled</option>
                    <option value="Arrived"> Arrived</option>
                    <option value="Checked"> Checked</option>
                    <option value="Admitted"> Admitted</option>
                    <option value="Discharged"> Discharged</option>
                    <option value="For follow"> For follow up</option>
                    <option value="Referred"> Referred Back</option>
                </select>
            </div>

            <div class="w-[15%] h-full flex flex-row justify-around items-center font-bold text-white">
                <button id='incoming-clear-search-btn' class="w-[100px] h-[90%] rounded bg-[#2f3e46]">Clear</button>
                <button id='incoming-search-btn' class="w-[100px] h-[90%] rounded bg-[#2f3e46]">Search</button>
            </div>
        </div>
        


        <section class=" w-[98%] h-[80%] flex flex-row justify-center items-center rounded-lg border-2 border-[#bfbfbf] mt-3">
            
            <div class="w-[98%] h-[95%]  flex flex-col justify-start rounded-lg overflow-y-auto">
                <table id="myDataTable" class="display">
                    <thead>
                        <tr class="text-center">
                            <th id="yawa" class="w-[20%] bg-[#e6e6e6]">Reference No. </th>
                            <th class="w-[17%] bg-[#e6e6e6]">Patient's Name</th>
                            <th class="w-[7%] bg-[#e6e6e6]">Type</th>
                            <th class="w-[17%] bg-[#e6e6e6]">Agency</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Date/Time</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Response Time</th>
                            <th class="w-[10%] bg-[#e6e6e6]"> Status</th>
                        </tr>
                    </thead>
                    <tbody id="incoming-tbody">
                        <?php
                            // SQL query to fetch data from your table
                            // echo  "here";
                            try{
                                $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // echo count($data);
                                
                                $index = 0;
                                $previous = 0;

                                // Loop through the data and generate table rows
                                foreach ($data as $row) {
                                    $type_color;
                                    if($row['type'] == 'OPD'){
                                        $type_color = 'bg-amber-600';
                                    }else if($row['type'] == 'OB'){
                                        $type_color = 'bg-green-500';
                                    }else if($row['type'] == 'ER'){
                                        $type_color = 'bg-sky-700';
                                    }

                                    
                                    if($previous == 0){
                                        $index += 1;
                                    }else{
                                        if($row['reference_num'] == $previous){
                                            $index += 1;
                                        }else{
                                            $index = 1;
                                        }  
                                    }
                                    echo '<tr class="h-[61px]">
                                            <td> ' . $row['reference_num'] . ' - '.$index.' </td>
                                            <td>' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                                            <td class="h-full font-bold text-center ' . $type_color . ' ">' . $row['type'] . '</td>
                                            <td>
                                                <label class="text-xs ml-1"> Referred: ' . $row['referred_by'] . '  </label>
                                                <div class="flex flex-row justify-start items-center"> 
                                                    <label class="text-[7.7pt] ml-1"> Landline: ' . $row['landline_no'] . ' </label>
                                                    <label class="text-[7.7pt] ml-1"> Mobile: ' . $row['mobile_no'] . ' </label>
                                                </div>
                                            </td>
                                            <td> 
                                                <label class="text-xs"> Referred: ' . $row['date_time'] . ' </label>
                                                <label class="text-xs"> Processed: asdf</label>
                                            </td>
                                            <td>
                                                <div class="flex flex-row justify-around items-center">
                                                    Processing: 
                                                    <div> 
                                                        <div class="stopwatch">00:00:00</div>
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class=" font-bold text-center bg-gray-500">
                                                <div class=" flex flex-row justify-around items-center"> 
                                                    
                                                    <label class="pat-status-incoming"> ' . $row['status'] . ' </label>
                                                    <i class="pencil-btn fa-solid fa-pencil cursor-pointer hover:text-white"></i>
                                                    <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                                                </div>
                                            </td>
                                        </tr>';

                                    $previous = $row['reference_num'];
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

            </div>
        </section>
    </div>

    <!-- MODAL -->
    <div id="pendingModal" class="fixed inset-0 flex items-center justify-center z-10 delay-100 hidden">
        <!-- Modal background -->
        <div class="modal-background fixed inset-0 bg-black opacity-50"></div>
            
            <!-- Modal content -->
            <div class="bg-black p-4 rounded shadow-md z-20  w-[1000px] h-[900px] flex flex-col justify-start items-start overflow-y-auto ">
                <div class="flex flex-row justify-between items-center w-full">
                    <label id="pending-type-lbl" class="bg-sky-600  w-[10%] justify-center text-white rounded-sm text-center"></label>
                    <div class="flex flex-row justify-between items-center w-[25%]">
                        <button class="bg-sky-600  w-[45%] justify-center text-white rounded-sm text-center">Print</button>
                        <button id="close-pending-modal" class="bg-sky-600  w-[45%] justify-center text-white rounded-sm text-center">Close</button>
                    </div>
                </div>
                <div class=" w-full h-[100px] mt-[1.5%] rounded-sm bg-white flex flex-col items-start justify-start">
                    <div class=" w-[100%] h-[40%] bg-gray-600 rounded-sm -mt-[0.1%] text-white font-semibold text-md flex flex-row justify-start items-center">
                        <label class="ml-[1%]">Status</label>
                    </div>
                   <label class="text-gray-500 font-bold ml-[1%] text-glow text-3xl">Pending</label>
                </div>

                <div class="bg-white w-full h-[180px] max-h-[180px] min-h-[180px]   mt-[1.5%] rounded-sm flex flex-col ">
                    <div class=" w-[100%] h-[18%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-col justify-center items-start">
                        <label class="text-black ml-[1%]">Patient Forwarding Form</label>
                        
                    </div>
                        
                 
                    <div class="flex flex-col justify-center items-start w-full  h-[45%] mt-[35px] ">
                    <label class="ml-[2%] font-semibold">Action</label>
                    <select class="border border-slate-800 w-[95%] ml-[2%] rounded-sm">
                        <option>Select</option>
                        <option>Emergency Room</option>
                        <option>OB-GYNE</option>
                        <option>Out-Patient Department</option>
                        
                       
                    </select>
                    <div class="flex flex-row justify-end items-center w-full">
                        <button id="pending-start-btn" class="bg-blue-400 font-semibold w-[10%] mt-4 mr-4 rounded-sm text-black"> Start </button>
                        <button class="bg-blue-400 font-semibold w-[10%] mt-4 mr-6 rounded-sm text-black"> Forward </button>
                        <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mt-4 mr-6 rounded-sm text-black"> Approved </button>
                    </div>
                    

                </div>
                    
                </div>


                <div class="bg-white w-full  mt-[1.5%] rounded-sm  flex flex-col">
                    <div class="bg-blue-400 rounded-sm text-black font-bold text-md p-2">
                        <label class="text-black">Referral Details</label>
                    </div>
                
                    <div class= "mt-2 p-2 flex flex-col">
                        <ul class="list-none flex flex-col space-y-2">
                            <li><label class="font-bold">Referring Agency:</label><span class="break-words"></span></li>
                            <li><label class="font-bold">Reason for Referral:</label><span class="break-words"></span></li><br>
                
                            <li><label class="font-bold">Name:</label><span id="pending-name"  class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Birthday:</label><span id="pending-bday" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Age:</label><span id="pending-age" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Sex:</label><span id="pending-sex" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Civil Status:</label><span id="pending-civil" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Religion:</label><span id="pending-religion" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Address:</label><span id="pending-address" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Parent/Guardian:</label><span id="pending-parent" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">PHIC Member:</label><span id="pending-phic" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Mode of Transport:</label><span id="pending-transport" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Date/Time Admitted:</label><span id="pending-admitted" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Referring Doctor:</label><span id="pending-referring-doc" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Contact #:</label><span id="pending-contact-no" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">OB-Gyne</label><span id="pending-ob" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Last Menstrual Period:</label><span id="pending-last-mens" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Age of Gestation</label><span id="pending-gestation" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Chief Complaint and History:</label><span id="pending-complaint-history" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Physical Examination</label><span id="pending-pe" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Blood Pressure:</label><span id="pending-bp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Heart Rate:</label><span id="pending-hr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Respiratory Rate:</label><span id="pending-rr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Temperature:</label><span id="pending-temp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Weight:</label><span id="pending-weight" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold">Fetal Heart Tone:</label><span id="pending-heart-tone" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Fundal Height:</label><span id="pending-fundal-height" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">Internal Examination</label><span id="pending-ie" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Cervical Dilatation:</label><span id="pending-cd" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Bag of Water:</label><span id="pending-bag-water" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Presentation:</label><span id="pending-presentation" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Others:</label><span id="pending-others" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Pertinent PE Findings:</label><span id="pending-p-pe-find" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Impression / Diagnosis:</label><span id="pending-diagnosis" class="break-words">This is where you put the data</span></li>
                        </ul>
                    </div>
                </div>

             </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-incoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Please fill out the required fields.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="./js/incoming_form.js?v=<?php echo time(); ?>"></script>

    <script>
    // $(document).ready(function () {
    //     $('#myDataTable').DataTable();
    // });

    
    </script>
</body>
</html>