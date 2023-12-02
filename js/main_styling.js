

// const nav_mobile_acc_div = document.querySelector('#nav-mobile-account-div');
// const nav_account_div = document.querySelector('#nav-account-div');
// const close_nav_mobile_btn = document.querySelector('#close-nav-mobile-btn')
// const nav_drop_account_div = document.querySelector('#nav-drop-account-div')

// nav_account_div.addEventListener('click', () => {
//     if(nav_drop_account_div.classList.contains("hidden")){
//         nav_drop_account_div.classList.remove('hidden')
//     }else{
//         nav_drop_account_div.classList.add('hidden')
//     }
//     nav_mobile_acc_div.classList.toggle('translate-x-32');
// });

// close_nav_mobile_btn.addEventListener('click', () => {
//     nav_mobile_acc_div.classList.toggle('translate-x-32');
// });

///////////////////////////////////////////////
// const side_bar_div = document.querySelector('#side-bar-div');
// const side_bar_mobile_btn = document.querySelector('#side-bar-mobile-btn');
// const patient_reg_form_div_1 = document.querySelector('#patient-reg-form-div-1')
// const patient_reg_form_div_2 = document.querySelector('#patient-reg-form-div-2')
// const patient_reg_form_div_3 = document.querySelector('#patient-reg-form-div-3')
// const license_div = document.querySelector('#license-div')

$(document).ready(function(){
    let current_page = ""

    const playAudio = () =>{
        let audio = document.getElementById("notif-sound")
        audio.muted = false;
        audio.play().catch(function(error){
            'Error playing audio: ' , error
        })
    }

    const stopSound = () =>{
        let audio = document.getElementById("notif-sound")
        audio.pause;
        audio.currentTime = 0;
    }

    function fetchMySQLData() {
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'bell'
            },
            success: function(data) {
                // console.log(data);
                $('#notif-span').text(data);
                if (parseInt(data) >= 1) {
                    $('#notif-circle').removeClass('hidden');
                    playAudio();
                } else {
                    $('#notif-circle').addClass('hidden');
                }
                
                setTimeout(fetchMySQLData, 5000);
            }
        });
    }

    fetchMySQLData(); 

    
    $('#logout-btn').on('click' , function(event){
        event.preventDefault(); // Prevent the default behavior (navigating to the link)

        $('#modal-title-main').text('Warning')
        // $('#modal-body').text('Are you sure you want to logout?')
        $('#ok-modal-btn-main').text('No')

        $('#yes-modal-btn-main').text('Yes');
        $('#yes-modal-btn-main').removeClass('hidden')

        $('#myModal-main').modal('show');
    })

    $('#yes-modal-btn-main').on('click' , function(event){
        console.log('here')
        document.querySelector('#nav-drop-account-div').classList.toggle('hidden');

        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds

        $.ajax({
            url: './php/save_process_time.php',
            data : {
                what: 'save',
                date : final_date
            },
            method: "POST",
            success: function(response) {
                // response = JSON.parse(response);  
                console.log(response)
                window.location.href = "./index.php" 
            }
        });
    })

    $('#ok-modal-btn-main').on('click' , function(event){
        console.log('asdfc')
    })

    $('#side-bar-mobile-btn').on('click' , function(event){
        event.preventDefault();
        document.querySelector('#side-bar-div').classList.toggle('-ml-[325px]');

        if(document.querySelector('#patient-reg-form-div-1')){
            if(document.querySelector('#patient-reg-form-div-1').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-2').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-3').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[25%]')
            }
        }
        
        
        if(document.querySelector('#license-div')){
            if(document.querySelector('#license-div').classList.contains('w-full')){
                document.querySelector('#license-div').classList.add('w-[80%]')
                document.querySelector('#license-div').classList.remove('w-full')
            }else{
                document.querySelector('#license-div').classList.remove('w-[80%]')
                document.querySelector('#license-div').classList.add('w-full')
            }
        }

        
    })

    $('#nav-account-div').on('click' , function(event){
        event.preventDefault();
        document.querySelector('#nav-drop-account-div').classList.toggle('hidden');

    })
})



const loadContent = (url) => {
    $.ajax({
        url:url,
        success: function(response){
            // console.log(response)
            $('#container').html(response);
        }
    })
}

// loadContent('php/default_view.php')
loadContent('php/patient_register_form.php')
// loadContent('php/opd_referral_form.php?type="ER"&code=BGHMC-0001')
// loadContent('php/incoming_form.php')
// loadContent('php/default_view.php')


$(document).ready(function(){
    $(window).on('load' , function(event){
        event.preventDefault();
        current_page = "default_page"
        $('#current-page-input').val(current_page)

        // loadContent('php/default_view.php')
        // loadContent('php/patient_register_form.php')
        // loadContent('php/opd_referral_form.php')
    })

    //$('#openModal')
    // $(window).on('load', function() {
    //     // $('#main-div').css('filter', 'blur(20px)');
    // });

    //welcome modal
    $('#closeModal').on('click' , function(event){
        $('#myModal').addClass('hidden')
        $('#main-div').css('filter', 'blur(0)');
        $('#modal-div').addClass('hidden')

        document.getElementById("notif-sound").play()
    })


    $(window).on('beforeunload', function() {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
    });

    if(parseInt($('#notif-circle').text()) > 0){
        // console.log("here")
        // document.getElementById("notif-sound").play()

        // setTimeout(function() {
        //     document.getElementById("notif-sound").play()
        // }, 2000); // Delay in milliseconds (2 seconds in this example)
    }

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        loadContent('php/default_view.php')

    //document.getElementById("notif-sound").pause();
    // document.getElementById("notif-sound").currentTime = 0;
    })

    // NOTIFICATION FUNCTIONS
    // let num_pending = 0;

    // $('.pat-status-incoming').each(function() {
    //     // 'this' refers to the current element in the loop
    //     let str = $(this).text();
    //     str = str.replace(/\s/g, '');

    //     if(str === 'Pending'){
    //         num_pending++;
    //     }
    // });

    // let value = parseInt($('#notif-span').text())
    // $('#notif-span').text(num_pending)



    $('#notif-div').on('click' , function(event){
        if($('#notif-span').val() === 0){
            $('#notif-circle').addClass('hidden')
            document.getElementById("notif-sound").pause();
            document.getElementById("notif-sound").currentTime = 0;
        }
    })

    // mikas
    // MIKAS3255



$(document).ready(function(){
    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();
        // // document.querySelector('#default-div').classList.add('hidden')
        // // Define your parameters
        // // Assuming your base URL is 'https://example.com/'
        // const baseUrl = 'http://192.168.42.222:8035/main.php';

        // // File path and parameters
        // const filePath = 'php/outgoing_form.php';
        // const pageParam = 'outgoing_patient_form';

        // // Construct the URL
        // const url = `${baseUrl}${filePath}?page=${pageParam}`;

        // // Use the constructed URL
        // loadContent(url);
        loadContent('php/outgoing_form.php')
        current_page = "outgoing_page"
        $('#current-page-input').val(current_page)

        })
    })
})

$(document).ready(function(){
    $('#incoming-sub-div-id').on('click' , function(event){
        event.preventDefault();
        loadContent('php/incoming_form.php')
        current_page = "incoming_page"
        $('#current-page-input').val(current_page)

    })
})

$(document).ready(function(){
    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();
        loadContent('php/patient_register_form.php')
        current_page = "patient_register_page"
        $('#current-page-input').val(current_page)

    })
})

$(document).ready(function(){
    $('#pcr-request-id').on('click' , function(event){
        event.preventDefault();
    })
    
})