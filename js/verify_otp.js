$(document).ready(function(){
    $('#otp-verify-btn').on('click' , function(event){
        console.log("here")
        let otp_input_1 = $('#otp-input-1').val().toString()
        let otp_input_2 = $('#otp-input-2').val().toString()
        let otp_input_3 = $('#otp-input-3').val().toString()
        let otp_input_4 = $('#otp-input-4').val().toString()
        let otp_input_5 = $('#otp-input-5').val().toString()
        let otp_input_6 = $('#otp-input-6').val().toString()
        let total = parseInt(otp_input_1 + otp_input_2 + otp_input_3 + otp_input_4 + otp_input_5 + otp_input_6)
        
        const data = {
            otp_number : total,
            hospital_code : $('#sdn-hospital-code').val(),
        }

        // console.log(data.otp_number, " type of " , typeof(data.otp_number))
        // console.log(total)
        $.ajax({
            url: './php/verify_otp.php',
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