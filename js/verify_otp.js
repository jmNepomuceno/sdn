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
                if(response === 'verified'){
                    console.log('here')
                    $('#myModal').modal('show');
                }
                // sdn_loading_modal_div.classList.remove('z-10')
                // sdn_loading_modal_div.classList.add('hidden')
                const otp_modal_div = document.querySelector('.otp-modal-div');
                otp_modal_div.classList.add('hidden')
                otp_modal_div.classList.remove('absolute')

                $('#sdn-hospital-name').val('')
                $('#sdn-hospital-code').val('')

                $('#sdn-region-select').val('')
                $('#sdn-province-select').val('')
                $('#sdn-city-select').val('')
                $('#sdn-brgy-select').val('')
                $('#sdn-zip-code').val('')
                $('#sdn-email-address').val('')
                $('#sdn-landline-no').val('')

                $('#sdn-hospital-mobile-no').val('')

                $('#sdn-hospital-director').val('')
                $('#sdn-hospital-director-mobile-no').val('')

                $('#sdn-point-person').val('')
                $('#sdn-point-person-mobile-no').val('')

                const sdn_modal_div = document.querySelector('.sdn-modal-div')
                const main_div = document.querySelector('.main-div')
                const modal_div = document.querySelector('.modal-div')
                
                sdn_modal_div.classList.add('hidden')
                sdn_modal_div.classList.remove('absolute')
                sdn_modal_div.style.zIndex = '0'

                main_div.style.filter = "blur(0)"
                modal_div.style.zIndex = '0'
                main_div.style.zIndex = '10'

                $('#otp-input-1').val("")
                $('#otp-input-2').val("")
                $('#otp-input-3').val("")
                $('#otp-input-4').val("")
                $('#otp-input-5').val("")
                $('#otp-input-6').val("")
            }
        })

    })
})