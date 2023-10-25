<?php
    // session_start();
    include('../database/connection2.php');
    // include('php/admin_module.php')
    // echo isset($_SESSION["user_name"]);    
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
    <link rel="stylesheet" href="../output.css">

    <style>
        .dataTables_length {
        margin-top:0;
        margin-bottom:5px;
    }   
    </style>

</head>
<body>
    <div class="w-full h-full flex flex-col justify-center items-center bg-white">
        <div class="w-full h-[10%] flex flex-row justify-around items-center -mt-[10px]">

            <div class="w-[10%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Referral No2222.</label>
                <input type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md outline-none">
            </div>
            

            <div class="w-[12%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Last Name</label>
                <input type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[10%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">First Name</label>
                <input type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[12%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">First Name</label>
                <input type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[6%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Case Type</label>
                <select class="w-full border-2 border-[#bfbfbf] rounded-md">
                    <option value="OB"> ER</option>
                    <option value="OB"> OB</option>
                    <option value="OB"> OPD</option>
                </select>
            </div>


            <div class="w-[15%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Agency</label>
                <select class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%">
                    <option value = "Balanga RHU">Balanga RHU</option>
                    <option value = "Abucay RHU">Abucay RHU</option>
                    <option value = "Orani RHU">Orani RHU</option>
                    <option value = "Limay RHU">Limay RHU</option>
                    <option value = "Mariveles RHU">Mariveles RHU</option>
                </select>
            </div>


            <div class="w-[9%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Status</label>
                <select class="w-full border-2 border-[#bfbfbf] rounded-md">
                    <option value="OB"> All</option>
                    <option value="OB"> On Process</option>
                    <option value="OB"> Deferred</option>
                    <option value="OB"> Aproved</option>
                    <option value="OB"> Cancelled</option>
                    <option value="OB"> Arrived</option>
                    <option value="OB"> Checked</option>
                    <option value="OB"> Admitted</option>
                    <option value="OB"> Discharged</option>
                    <option value="OB"> For follow up</option>
                    <option value="OB"> Referred Back</option>
                </select>
            </div>


            <div class="w-[9.5%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Show</label>
                <input type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>
        </div>
        


        <section class=" w-[98%] h-[80%] flex flex-row justify-center items-center rounded-lg border-2 border-[#bfbfbf] mt-3">
            
            <div class="w-[98%] h-[95%]  flex flex-col justify-start rounded-lg overflow-y-auto">
                <!-- <label class="">Showing Results</label> -->

                <!-- <table class="h-[94%]">
                    <tr class="flex flex-row justify-start items-center text-center">
                        <th class="w-[20%] bg-[#e6e6e6]">Reference No. </th>
                        <th class="w-[17%] bg-[#e6e6e6]">Patient's Name</th>
                        <th class="w-[7%] bg-[#e6e6e6]">Type</th>
                        <th class="w-[17%] bg-[#e6e6e6]">Agency</th>
                        <th class="w-[15%] bg-[#e6e6e6]">Date/Time</th>
                        <th class="w-[15%] bg-[#e6e6e6]">Response Time</th>
                        <th class="w-[10%] bg-[#e6e6e6]"> Status</th>
                    </tr>
                    <tr class="w-full h-[100px] rounded-lg flex flex-row items-center rounded-lg mt-2 bg-[#e6e6e6]">
                        <td class="w-[19.8%] h-full flex flex-row justify-start items-center">
                            <p class="w-[100%] text-left font-bold text-center">
                                R3-BTN-PHO-2023-09-22-1
                            </p>
                        </td>

                        <td class="w-[16.7%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-left text-center">
                                DORONILA, ROBERTO PEDROSA
                            </p>
                        </td>

                        <td class="w-[7%] h-full flex flex-row justify-center items-center ">
                            <div class="w-[80%] bg-green-600 h-full"></div>
                        </td> 



                        <td class="w-[16.9%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%] text-center">
                                Provincial Health Office
                                Landline:0
                                Mobile:
                                9170000000                       
                            </p>
                        </td>

                        <td class="w-[15%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-center">
                                Referred:09/22/2023 - 13:34:14
                            </p>
                        </td>      
            
                        
                        <td class="w-[14.6%] h-full text-center">
                            <div></div>
                        </td> 
                    
                        <td class="w-[10%] h-full flex flex-row justify-around items-top">
                            <div class="w-[50%] h-full bg-green-700"></div>
                            <div class="fa-solid fa-pen-to-square text-xl cursor-pointer mt-2"></div>
                        </td>
                    </tr>
                    
                    <tr class="w-full h-[100px] rounded-lg flex flex-row items-center rounded-lg mt-2 bg-[#e6e6e6]">
                        <td class="w-[19.8%] h-full flex flex-row justify-start items-center">
                            <p class="w-[100%] text-left font-bold text-center">
                                R3-BTN-PHO-2023-09-22-1
                            </p>
                        </td>

                        <td class="w-[16.7%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-left text-center">
                                asd, ROBERTO PEDROSA
                            </p>
                        </td>

                        <td class="w-[7%] h-full flex flex-row justify-center items-center ">
                            <div class="w-[80%] bg-green-600 h-full"></div>
                        </td> 



                        <td class="w-[16.9%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%] text-center">
                                Provincial Health Office
                                Landline:0
                                Mobile:
                                9170000000                       
                            </p>
                        </td>

                        <td class="w-[15%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-center">
                                Referred:09/22/2023 - 13:34:14
                            </p>
                        </td>      
            
                        
                        <td class="w-[14.6%] h-full text-center">
                            <div></div>
                        </td> 
                    
                        <td class="w-[10%] h-full flex flex-row justify-around items-top">
                            <div class="w-[50%] h-full bg-green-700"></div>
                            <div class="fa-solid fa-pen-to-square text-xl cursor-pointer mt-2"></div>
                        </td>
                    </tr>

                    <tr class="w-full h-[100px] rounded-lg flex flex-row items-center rounded-lg mt-2 bg-[#e6e6e6]">
                        <td class="w-[19.8%] h-full flex flex-row justify-start items-center">
                            <p class="w-[100%] text-left font-bold text-center">
                                R3-BTN-PHO-2023-09-22-1
                            </p>
                        </td>

                        <td class="w-[16.7%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-left text-center">
                                fff, ROBERTO PEDROSA
                            </p>
                        </td>

                        <td class="w-[7%] h-full flex flex-row justify-center items-center ">
                            <div class="w-[80%] bg-green-600 h-full"></div>
                        </td> 



                        <td class="w-[16.9%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%] text-center">
                                Provincial Health Office
                                Landline:0
                                Mobile:
                                9170000000                       
                            </p>
                        </td>

                        <td class="w-[15%] h-full flex flex-row justify-center items-center">
                            <p class="w-[100%]  text-center">
                                Referred:09/22/2023 - 13:34:14
                            </p>
                        </td>      
            
                        <td class="w-[14.6%] h-full text-center">
                            <div></div>
                        </td> 
                    
                        <td class="w-[10%] h-full flex flex-row justify-around items-top">
                            <div class="w-[50%] h-full bg-green-700"></div>
                            <div class="fa-solid fa-pen-to-square text-xl cursor-pointer mt-2"></div>
                        </td>
                    </tr>
                    
                </table> -->

                <!-- <table id="myDataTable" class="display">
                    <thead>
                        <tr class="text-center">
                            <th class="w-[20%] bg-[#e6e6e6]">Reference No. </th>
                            <th class="w-[17%] bg-[#e6e6e6]">Patient's Name</th>
                            <th class="w-[7%] bg-[#e6e6e6]">Type</th>
                            <th class="w-[17%] bg-[#e6e6e6]">Agency</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Date/Time</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Response Time</th>
                            <th class="w-[10%] bg-[#e6e6e6]"> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // SQL query to fetch data from your table
                        $sql = "SELECT * FROM hperson ORDER BY created_at DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $index = 0;
                        $previous = 0;

                        // Loop through the data and generate table rows
                        foreach ($data as $row) {
                            $dateTimeString =  $row['created_at'];
                            $dateTime = new DateTime($dateTimeString);

                            // Format the DateTime object as needed
                            $formattedDateTime = $dateTime->format("Y-m-d H:i:s");

                            $year = $dateTime->format("Y");
                            $month = $dateTime->format("m");
                            $day = $dateTime->format("d");
                            $hours = $dateTime->format("H");
                            $minutes = $dateTime->format("i");
                            $seconds = $dateTime->format("s");

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
                                if($day == $previous){
                                    $index += 1;
                                }else{
                                    $index = 1;
                                }  
                            }
                            echo '<tr class="h-[61px]">
                                    <td>R3-BTN-BGHMC-' . $year . '-' . $month . '-' . $day . '-' . $index . '</td>
                                    <td>' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                                    <td class="h-full font-bold text-center ' . $type_color . ' ">' . $row['type'] . '</td>
                                    <td>
                                        <label class="text-sm ml-1"> Referred: Limay District Hospital </label>
                                    </td>
                                    <td> 
                                       <label> Referred: ' . $row['created_at'] . ' </label>
                                    </td>
                                    <td>Processing: </td>
                                    <td class=" font-bold text-center bg-gray-500">
                                        <div class="flex flex-row justify-around items-center"> 
                                            ' . $row['status'] . '

                                            <i class="fa-solid fa-pencil cursor-pointer hover:text-white"></i>
                                        </div>
                                    </td>
                                </tr>';

                            $previous = $day;
                        }

                        // Close the database connection
                        $pdo = null;
                        ?>
                    </tbody>
                </table> -->

            </div>
        </section>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="./js/incoming_form.js?v=<?php echo time(); ?>"></script>

    <script>
    // $(document).ready(function () {
    //     $('#myDataTable').DataTable();
    // });

    $(document).ready(function() {
        $('#myDataTable').DataTable({
            "order": [[0, "desc"]] // Sort the first column in descending order (change column index as needed)
        });
        var dataTable = $('#myDataTable').DataTable();

        // Disable the search input
        dataTable.search('').draw();

        // Disable the search button
        $('.dataTables_filter').addClass('hidden');

        
    });
</script>
</body>
</html>