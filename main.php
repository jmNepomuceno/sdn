<?php
    // include("../database/connection2.php");  
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="index.css">

    <script>
        tailwind.config = {
            theme: {
            extend: {
                height: {
                    300: '300px',
                    82: '82px',
                },
                width: {
                    450: '450px',   
                },
                margin:{
                    415: '415px',
                    50 : '50px'
                },
                backgroundColor: {
                    mainColor: '#2f3e46',
                    // mainColor: '#138275',
                    // mainColor : '#028910',
                    // mainColor : '#3cec97',
                    loginHereBtn : '#198754',
                    teleCreateAccColor : '#e6e6e6'
                },
                borderColor: {
                    // loginBorder: '#f2f2f2',
                    // mainColor: '#2f3e46',
                    subDivColor: '#2f3e46',
                    // titleDivColor : '#94abb8'
                    titleDivColor : '#3cec97',
                    sdnRegistraionColor : '#999999',
                },
                borderWidth:{
                    415 : '415px'
                }
            } 
            }
        }
    </script>


</head>
<body>
    <div class="flex flex-col justify-between items-left w-full h-screen overflow-hidden">
        <header class="header-div w-full h-[70px] flex flex-col flex-row justify-end items-center bg-[#1f292e]">
            <div class="account-header-div w-[25%] h-full border-2 border-white flex flex-row justify-end items-center">

                <div class="w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                    <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                        <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                    </div> -->
                    
                    <div class="w-[20px] h-full flex flex-col justify-center border items-center">
                        <h1 class="absolute top-2 text-center w-[17px] h-[17px] rounded-full bg-red-600 ml-5 text-white text-xs">6</h1>
                        <i class="fa-solid fa-bell text-white text-xl"></i>
                    </div>

                    <div class="w-[20px] h-full flex flex-col justify-center items-left border">
                        <i class="fa-solid fa-caret-down text-white text-xs"></i>
                    </div>
                </div>

                <div class="header-username-div w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                    <div class="w-[15%] h-full flex flex-row justify-end items-center mr-1">
                        <i class="fa-solid fa-user text-white text-xl"></i>
                    </div>
                    <div id="" class="w-auto h-full whitespace-nowrap flex flex-col justify-center items-center cursor-pointer">
                        <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1>
                    </div>
                    <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                        <i class="fa-solid fa-caret-down text-white text-xs"></i>
                    </div>

                </div>
            </div>
        </header>

        <div class="w-2/4 h-full bg-red-600 absolute right-0"></div>

        <!-- <aside class="side-bar-div w-[17%] h-full flex flex-col justify-start items-center bg-mainColor">

            <div id="main-side-bar-1" class="w-full h-auto flex flex-col justify-start items-center">
                <div class="w-full h-[50px] flex flex-row justify-between items-center">
                    <h3 class="m-3 text-xl text-white">Patient Registration</h3>
                </div>

                <div id="sub-side-bar-1" class="w-full h-auto bg-[#1f292e]">
                    <div class="w-full h-[50px] flex flex-row justify-between items-center ">
                        <h3 class="m-16 text-xl text-white">Paitent Form</h3>
                    </div>
                </div>
            </div>

            <div id="main-side-bar-2" class="w-full h-auto flex flex-col justify-start items-center">
                <div class="w-full h-[50px] flex flex-row justify-between items-center">
                    <h3 class="m-3 text-xl text-white">Online Referral/h3>
                </div>

                <div id="sub-side-bar-2" class="w-full h-auto bg-[#1f292e]">
                    <div class="w-full h-[50px] flex flex-row justify-between items-center border-b border-[#29363d]">
                        <h3 class="m-16 text-xl text-white">Outgoing</h3>
                    </div>
                    <div class="w-full h-[50px] flex flex-row justify-between items-center border-b border-[#29363d]">
                        <h3 class="m-16 text-xl text-white">Incoming</h3>
                    </div>
                    <div class="w-full h-[50px] flex flex-row justify-between items-center border-b border-[#29363d]">
                        <h3 class="m-16 text-xl text-white">PCR Request List</h3>
                    </div>
                </div>
            </div>


        </aside> -->
    </div>

    <script src="./js/main_styling.js?v=<?php echo time(); ?>"></script>

</body>
</html>