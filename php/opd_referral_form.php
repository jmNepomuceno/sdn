<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="w-[85%] h-[80%] flex flex-col">
        

        <div class="w-full">
            <label class="font-semibold text-2xl ml-[1%] ">
                OPD Referral Form
            </label>
        </div>

        <div class="w-full h-full flex flex-col justify-center items-center">
            <div class="w-full h-[15%] flex flex-row items-center justify-start">

                <div class="w-[70%] h-full flex flex-row justify-start items-center">
                    <div class="w-[50%] h-full flex flex-col justify-center items-left ml-5">
                        <label class="font-bold -ml-[1.7%]">Refer to</label>    
                        <select class="rounded-md w-full border-2 p-1 border-[#bfbfbf] -ml-[1.7%]">
                            <option value="Disabled Selected">Select</option>
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

                    <div class="w-[30%] h-full ml-[4%] flex flex-col justify-center items-left">
                        <label class="ml-1 justify-center items-center  font-bold mt-3">
                            Sensitive Case 
                            <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none cursor-pointer">
                        </label>
                        <div class="w-full h-[40px]">
                            <input type="radio" name="group1" class="ml-[5%]"> 
                            <label class="mb-[0.3%] ml-[0.2%]">Yes</label>
                            <input type="radio" name= "group1" class="ml-[2%]">
                            <label class="mb-[0.3%] ml-[0.2%]">No</label>
                        </div>
                    </div>
                </div>

                
                <a href="#" class="hover:text-blue-500 hover:font-bold ml-[15%]">
                    Check Bed Availability
                </a>
            </div>   

            <div class="w-full h-[11%] flex flex-row justify-around items-start font-bold">
                <div class="w-[30%] flex flex-col">
                    <label class="-ml-[2%]">Parent/Guardian(If minor)</label>
                    <input type="textbox" class="rounded-md  w-[98%] border-2  border-[#bfbfbf] -ml-[2%]">
                </div>
                
                <div class="ml-[2%] w-[15%]">
                    <label class="ml-[0.5%]">PHIC Member?</label>
                    <select class="rounded-md ml-[0.5%] w-[100%] border-2  border-[#bfbfbf]">
                        <option value="Disabled Selected">Select</option>
                        <option value="OB"> Yes</option>
                        <option value="OB"> No </option>
                    </select>
                </div>

                <div class="ml-[2%] w-[15%]">
                    <label class="ml-[0.5%]">Mode of Transport</label>
                    <select class="rounded-md ml-[0.5%] w-[100%] border-2  border-[#bfbfbf]">
                        <option value="Disabled Selected">Select</option>
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

                <div class="ml-[2%] w-[23%]">
                    <label class="ml-[0.5%]">Date/Time Admitted</label>
                    <input type="datetime" class="rounded-md ml-[%] w-[100%] border-2  border-[#bfbfbf] ">
                </div>   
            </div>

            <div class="w-full h-[74%] flex flex-row justify-center items-center">

                <div class="w-[50%] h-full flex flex-col justify-center items-center">
                    
                    <div class="w-[97%] h-[12%] flex flex-col">
                        <label class="font-bold">Referring Doctor</label>
                        <select class="rounded-md w-[100%] border-2  border-[#bfbfbf]">
                            <option value="Disabled Selected">Select</option>
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

                    <div class="w-[97%] h-[87%] flex flex-col justify-start items-left">
                        <label class="w-full font-bold ">Chief Complaint and History</label>
                        <textarea class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none "></textarea>

                        <label class="w-full font-bold  ">Reason for Referral</label>
                        <textarea class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none"></textarea>

                        <label class="w-full font-bold  ">Impression / Diagnosis</label>
                        <textarea class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none"></textarea>
                    </div>
                </div>

                <div class="w-[50%] h-full flex flex-col justify-between items-left">
                    <label class="w-full h-[20px] ml-3 font-bold">Physical Examination</label>
                    <div class="w-[97%] h-[95%] border-2 border-[#bfbfbf] rounded-lg ml-[1.5%] flex flex-col justify-center items-center">
                        <div class="flex flex-row w-[98%] h-[14%]  ml-[6px] mt-2 justify-center items-center">
                            <div class="w-[20%]">
                                <label class="font-semibold">
                                    BP          
                                    <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none">
                                    <input type="textbox" class="border-2  border-[#bfbfbf] w-[98%] h-[40%]">                      
                                </label>
                            </div>

                            <div class="w-[20%]  ml-[3%]">
                                <label class="font-semibold">
                                    HR          
                                    <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none">
                                    <input type="textbox" class="border-2  border-[#bfbfbf] w-[98%]">                      
                                </label>
                            </div>

                            <div class="w-[20%]  ml-[3%]">
                                <label class="font-semibold">
                                    RR          
                                    <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none">
                                    <input type="textbox" class="border-2  border-[#bfbfbf] w-[98%]">                      
                                </label>
                            </div>



                            <div class="w-[20%]  ml-[3%]">
                                <label class="font-semibold">
                                    Temp (°C)          
                                    <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none">
                                    <input type="textbox" class="border-2  border-[#bfbfbf] w-[98%]">                      
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-row w-[20%] h-[12%] mt-[10px] justify-center items-center ">
                            <div class="w-[98%]">
                                <label class="font-semibold">
                                    Wt.(kg)          
                                    <input type="button" class="w-3 h-3 rounded-full bg-blue-500 text-white hover:bg-blue-700 focus:outline-none">
                                    <input type="textbox" class="border-2  border-[#bfbfbf] w-[98%] h-[40%]">                      
                                </label>
                            </div>
                        </div> 

                        <div class="w-[90%] h-[70%]">
                            <label class="ml-2 font-bold">Pertinent PE Findings</label>
                            <textarea class="border-2 border-[#bfbfbf] w-[98%] h-[88%] ml-[1%] rounded-lg"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>