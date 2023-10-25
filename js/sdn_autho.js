$(document).ready(function(){
    $('#sdn-autho-verify-btn').on('click' , function(event){
        event.preventDefault();
        console.log("here")
        //$sdn_autho_id = array("sdn-auth-hospital-code", "sdn-cipher-key" , "sdn-last-name", "sdn-first-name", "sdn-middle-name", "sdn-extension-name", "sdn-username" , "sdn-password", "sdn-confirm-password");
        const currentDateTime = new Date();
        const year = currentDateTime.getFullYear();
        const month = currentDateTime.getMonth() + 1; // Month is zero-based, so add 1 to get the correct month.
        const day = currentDateTime.getDate();
        const hours = currentDateTime.getHours();
        const minutes = currentDateTime.getMinutes();
        const seconds = currentDateTime.getSeconds();
        let created_at = (`${year}-${month}-${day} ${hours}:${minutes}:${seconds}`)
        // console.log(created_at);
        // USER COUNT SAKA USER TYPE GL HF TOMORROW :)))))))
        const data = {
            hospital_code : parseInt($('#sdn-autho-hospital-code-id').val()),

            cipher_key : $('#sdn-autho-cipher-key-id').val(),
            last_name : $('#sdn-autho-last-name-id').val(),
            first_name : $('#sdn-autho-first-name-id').val(),
            middle_name : $('#sdn-autho-middle-name-id').val(),
            extension_name : $('#sdn-autho-ext-name-id').val(),
            user_name : $('#sdn-autho-username').val(),
            pass_word : $('#sdn-autho-password').val(),
            confirm_password : $('#sdn-autho-confirm-password').val(),
            created_at : created_at,
            user_type: 'Sample',
            user_isActive: false
        }
// 
        console.log(data)

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                console.log(key + " -> " + data[key] + " -> " + typeof data[key]);
            }
        }

        $.ajax({
            url: './php/sdn_autho.php',
            method: "POST",
            data:data,
            success: function(response){
                console.log(response)
                if(response === 'maximum'){
                    $('#modal-title').text('Warning')
                    $('#modal-body').text('Maximum number of users has already been sign up from your Hospital.')
                    $('#myModal').modal('show'); 
                }
                if(response === 'not valid'){
                    $('#modal-title').text('Warning')
                    $('#modal-body').text('Your hospital code is not registered with our database yet.')
                    $('#myModal').modal('show'); 
                }
                if(response === 'success'){
                    $('#modal-title').text('Successed')
                    $('#modal-icon').addClass('fa-circle-check')
                    $('#modal-body').text('Successfully Created an account!')
                    $('#myModal').modal('show'); 
                }
                // sdn_loading_modal_div.classList.remove('z-10')
                // sdn_loading_modal_div.classList.add('hidden')
                // const otp_modal_div = document.querySelector('.otp-modal-div');
                // otp_modal_div.className = "otp-modal-div z-10 absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-white rounded-lg"

            }
        })
    })
})

