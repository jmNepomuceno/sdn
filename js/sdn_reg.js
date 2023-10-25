$(document).ready(function(){
    $('#sdn-register-btn').on('click' , function(event){
        event.preventDefault();

        // $sdn_id = array("sdn-hospital-name","sdn-hospital-code","sdn-address-region","sdn-address-province", "sdn-address-municipality" ,"sdn-address-barangay","sdn-zip-code" ,"sdn-email-address" ,
        //             "sdn-landline-no" ,"sdn-hospital-mobile-no", "sdn-hospital-director", "sdn-hospital-director-mobile-no","sdn-point-person","sdn-point-person-mobile-no");

        // $sdn_id = array("sdn-hospital-name","sdn-hospital-code","sdn-address-region","sdn-address-province", "sdn-address-municipality" ,"sdn-address-barangay","sdn-zip-code" ,"sdn-email-address" ,
        // "sdn-landline-no" ,"sdn-hospital-mobile-no", "sdn-hospital-director", "sdn-hospital-director-mobile-no","sdn-point-person","sdn-point-person-mobile-no");
        // let temp 
        // document.querySelector('#sdn-region-select').addEventListener('change', function(){
        //     console.log(document.querySelector('#sdn-region-select').value)
        //     temp = document.querySelector('#sdn-region-select').value
        // })
        
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

                }
                
            }
        })
    })
    //get the user typed OTP
})

