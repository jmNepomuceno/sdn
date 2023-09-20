$(document).ready(function(){
    $('#sdn-otp-modal-btn-close').on('click' , function(event){
        // main OTP modal div
        const otp_modal_div = document.querySelector('.otp-modal-div')

        // close button for OTP modal
        const sdn_otp_modal_btn_close = document.querySelector('.sdn-otp-modal-btn-close')

        // handle close button for otp modal
        let response= confirm("Do you want to cancel the registration?");
        if (response == true) {
            otp_modal_div.className = "otp-modal-div hidden absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-teleCreateAccColor rounded-lg"
        }else {
            alert("You may proceed, but you will only have limited access to features on the website.");
        }

        const data = {
            hospital_code : $('#sdn-hospital-code').val(),
        }
        console.log("closed, " + 1)
        $.ajax({
            url: './php/closed_otp.php',
            method: "POST",
            data:data,
            success: function(response){
                console.log(response)
                // sdn_loading_modal_div.classList.remove('z-10')
                // sdn_loading_modal_div.classList.add('hidden')
                // const otp_modal_div = document.querySelector('.otp-modal-div');
                // otp_modal_div.className = "otp-modal-div z-10 absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-white rounded-lg"

            }
        })

    })
})