<?php
    session_start();
    include('database/connection2.php');
    // echo isset($_SESSION["user_name"]);

    // $user = $_SESSION['user_name'];
    // $user = $_SESSION['user_name'];
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

    <script>
        //using query parameters
        // var queryParams = new URLSearchParams(window.location.search);
        // var dataReceived = queryParams.get("user"); 
        // console.log(dataReceived); // Outputs: "Hello World"

        var dataReceived = sessionStorage.getItem("user");
    // console.log(dataReceived); // Outputs: "Hello World"
    </script>
</head>
<body>
    <div id="main-div" class="flex flex-col justify-between items-left w-full h-screen overflow-x-hidden">
        <header class="header-div w-full h-[50px] flex flex-col flex-row justify-between items-center bg-[#1f292e]">
            <div class="w-[30%] h-full flex flex-row justify-start items-center">
                <div id="side-bar-mobile-btn" class="side-bar-mobile-btn w-[10%] h-full flex flex-row justify-center items-center cursor-pointer">
                    <i class="fa-solid fa-bars text-white text-4xl"></i>
                </div>
                <h1 id="sdn-title-h1" class="text-white text-2xl ml-2 cursor-pointer"> Service Delivery Network</h1>
            </div>
            <div class="account-header-div w-[25%] h-full flex flex-row justify-end items-center mr-2">

                <div class="w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                    <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                        <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                    </div> -->
                    
                    <div class="w-[20px] h-full flex flex-col justify-center items-center">
                        <h1 class="absolute top-2 text-center w-[17px] h-[17px] rounded-full bg-red-600 ml-5 text-white text-xs">6</h1>
                        <i class="fa-solid fa-bell text-white text-xl"></i>
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
                        <h1 class="text-white text-lg hidden sm:block"><?php echo $_SESSION['user_name'] ?></h1>
                        
                    </div>
                    <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                        <i class="fa-solid fa-caret-down text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>
        <!-- <i class="fa-solid fa-bars"></i> -->
        <!-- <div id="nav-mobile-account-div" class="overflow-hidden w-2/3 h-full bg-[#1f292e] absolute -right-[70%] transition-transform duration-300">
            
        </div> -->

        <div id="nav-mobile-account-div" class="sm:hidden flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-64 h-full overflow-y-auto transition-transform duration-300 transform translate-x-96 z-10">
            <div id="close-nav-mobile-btn" class="w-full h-[50px] mt-2 flex flex-row justify-start items-center">
                <i class="fa-solid fa-x ml-2 text-2xl"></i>
            </div>
            <div class="w-full h-[350px] flex flex-col justify-around items-center">
                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Dashboard (Incoming)</h2>
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
                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Dashboard (Incoming)</h2>
                    </div>

                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
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
                        <h2 class="">Logout</h2>
                    </div>
                </div>
        </div>

        <div class="flex flex-row justify-start items-center w-full h-full"> 

            <aside id="side-bar-div" class="z-10 side-bar-div text-lg w-[17%] h-full flex flex-col justify-start items-center bg-mainColor duration-200 ml-0">
                <div class="w-[95%] h-[10%] flex flex-row justify-center items-center text-center border-b-4 border-[#29363d]">
                    <img src="assets/login_imgs/main_bg.png" alt="logo-img" class="w-[28%] h-[75%]" />
                    <p class="text-white text-sm w-[65%]">Bataan General Hospital and Medical Center</p>
                </div>

                <div id="main-side-bar-1" class="w-full h-auto flex flex-col justify-start items-center cursor-pointer text-base">
                    <div class="w-full h-[50px] flex flex-row justify-start items-center">
                        <i class="ml-3 fa-solid fa-hospital-user text-lg text-white opacity-80"></i>
                        <h3 class="ml-3 mt-1 text-white">Patient Registration</h3>
                    </div>

                    <div id="sub-side-bar-1" class="w-full h-auto bg-[#1f292e]">
                        <div id="patient-reg-form-sub-side-bar" class="w-full h-[50px] flex flex-row justify-start items-center opacity-30 hover:opacity-100 duration-150">
                            <i class="ml-8 fa-solid fa-hospital-user text-lg text-white opacity-80"></i>  
                            <h3 class="ml-2 text-white">Patient Registration Form</h3>
                        </div>
                    </div>
                </div>

                <div id="main-side-bar-2" class="w-full h-auto flex flex-col justify-start items-center cursor-pointer text-base">
                    <div class="w-full h-[50px] flex flex-row justify-start items-center">
                        <i class="ml-3 fa-solid fa-retweet text-lg text-white opacity-80"></i>
                        <h3 class="m-3 text-white">Online Referral </h3>
                    </div>

                    <div id="sub-side-bar-2" class="w-full h-auto bg-[#1f292e]">
                        <div id="outgoing-sub-div-id" class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                            <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                            <h3 class="m-3 text-white">Outgoing</h3>
                        </div>
                        <div class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                            <!-- <h3 class="m-16 text-white">Incoming</h3> -->
                            <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                            <h3 class="m-3 text-white">Incoming</h3>
                        </div>
                        <div id="pcr-request-id" class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                            <!-- <h3 class="m-16 text-white">PCR Request List</h3> -->
                            <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                            <h3 class="m-3 text-white">PCR Request List</h3>
                        </div>
                    </div>
                </div>
            </aside>

            
            
            

            <div id="container" class="w-full h-full flex flex-row justify-center items-center">
            
            </div>
            <!-- ADMIN MODULE -->

            
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="./js/main_styling.js?v=<?php echo time(); ?>"></script>
    <script src="./js/patient_register_form.js?v=<?php echo time(); ?>"></script>
    <script src="./js/location.js?v=<?php echo time(); ?>"></script>
    <script src="./js/search_name.js?v=<?php echo time(); ?>"></script>

</body>
</html>