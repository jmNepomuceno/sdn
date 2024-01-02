$(document).ready(function(){
    $('#sdn-register-btn').on('click' , function(event){
        event.preventDefault();

        const data = {
            hospital_name : $('#sdn-hospital-name').val(),
            hospital_code : $('#sdn-hospital-code').val(),

            region : $('#sdn-region-select').val(),
            province : $('#sdn-province-select').val(),
            municipality : $('#sdn-city-select').val(),
            barangay : $('#sdn-brgy-select').val(),
            zip_code : $('#sdn-zip-code').val(),
            email : $('#sdn-email-address').val(),
            landline_no : $('#sdn-landline-no').val(),

            hospital_mobile_no : $('#sdn-hospital-mobile-no').val(),

            hospital_director : $('#sdn-hospital-director').val(),
            hospital_director_mobile_no : $('#sdn-hospital-director-mobile-no').val(),

            point_person : $('#sdn-point-person').val(),
            point_person_mobile_no : $('#sdn-point-person-mobile-no').val(),
        }

        // console.log(data.region)

        const sdn_loading_modal_div = document.querySelector('.sdn-loading-div')
        sdn_loading_modal_div.classList.add('z-10')
        sdn_loading_modal_div.classList.remove('hidden')
        $.ajax({
            url: './php/sdn_reg.php',
            method: "POST",
            data:data,
            success: function(response){
                if(response === 'Invalid'){
                    sdn_loading_modal_div.classList.add('hidden')
                    $('#modal-title').text('Warning')
                    $('#modal-icon').addClass('fa-triangle-exclamation')
                    $('#modal-icon').removeClass('fa-circle-check')
                    $('#modal-body').text('Hospital Code is already registered!')
                    $('#ok-modal-btn').text('OK')

                    $('#myModal').modal('show');
                }else{
                    sdn_loading_modal_div.classList.remove('z-10')
                    sdn_loading_modal_div.classList.add('hidden')
                    const otp_modal_div = document.querySelector('.otp-modal-div');
                    otp_modal_div.className = "otp-modal-div z-10 absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-white rounded-lg"
                    $('#otp-input-1').focus()

                    // Set the countdown duration in seconds (5 minutes)
                    const countdownDuration = 300;
                    
                    // Get the timer element
                    const timerElement = document.getElementById('resend-otp-timer');

                    // Initialize the countdown value
                    let countdown = countdownDuration;

                    // Update the timer display function
                    function updateTimer() {
                    const minutes = Math.floor(countdown / 60);
                    const seconds = countdown % 60;

                    // Display minutes and seconds
                    timerElement.textContent = `Resend OTP after: ${minutes}m ${seconds}s`;

                    // Check if the countdown has reached zero
                    if (countdown === 0) {
                        clearInterval(timerInterval); // Stop the timer
                        timerElement.textContent = '00:00';
                        $('#resend-otp-btn').removeClass('opacity-50 pointer-events-none')
                    } else {
                        countdown--; // Decrement the countdown
                    }
                    }

                    // Set up the timer to update every second
                    const timerInterval = setInterval(updateTimer, 1000);

                }
                
            }
        })
    })
    //get the user typed OTP
})

