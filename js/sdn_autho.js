$(document).ready(function(){
    $('#sdn-autho-register-btn').on('click' , function(event){
        event.preventDefault();
        console.log("here")
        //$sdn_autho_id = array("sdn-auth-hospital-code", "sdn-cipher-key" , "sdn-last-name", "sdn-first-name", "sdn-middle-name", "sdn-extension-name", "sdn-username" , "sdn-password", "sdn-confirm-password");

        const data = {
            hospital_code : $('#sdn-hospital-code').val(),

            cipher_key : $('#sdn-cipher-key').val(),
            last_name : $('#sdn-last-name').val(),
            first_name : $('#sdn-first-name').val(),
            middle_name : $('#sdn-middle-name').val(),
            extension_name : $('#sdn-extension-name').val(),
            user_name : $('#sdn-username').val(),
            pass_word : $('#sdn-password').val(),
        }

        $.ajax({
            url: './php/sdn_autho.php',
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

