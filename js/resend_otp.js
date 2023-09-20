$(document).ready(function(){
    $('#resend-otp-btn').on('click' , function(event){
        function generateRandomNumber(min, max) {
            // console.log("w/out " , Math.random() * (max - min + 1))
            return Math.floor(Math.random() * (max - min + 1) + min);
        }
        
        let OTP = generateRandomNumber(100000, 999999);
        const data = {
            hospital_code : $('#sdn-hospital-code').val(),
            OTP : OTP,
        }
        $.ajax({
            url: './php/resend_otp.php',
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