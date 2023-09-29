$(document).ready(function(){
    

    $('#check-if-registered-btn').on('click' , function(event){
        console.log("here")
        const check_if_registered_btn = document.querySelector('#check-if-registered-btn');
        const check_if_registered_div = document.querySelector('#check-if-registered-div')

        if(check_if_registered_div.classList.contains('hidden')){
            check_if_registered_div.classList.remove('hidden')
    
            check_if_registered_btn.classList.remove('rounded-lg')
            check_if_registered_btn .classList.add('rounded-t-lg')
        }else{
            check_if_registered_div.classList.add('hidden')
    
            check_if_registered_btn.classList.add('rounded-lg')
            check_if_registered_btn .classList.remove('rounded-t-lg')
        }
    })

    $('#same-as-perma-btn').on('click' , function(event){
        // console.log(document.querySelector('#hperson-province-select-pa').value)

        document.querySelector('#hperson-house-no-ca').value = document.querySelector('#hperson-house-no-pa').value
        document.querySelector('#hperson-street-block-ca').value = document.querySelector('#hperson-street-block-pa').value
        document.querySelector('#hperson-region-select-ca').value = document.querySelector('#hperson-region-select-pa').value
        document.querySelector('#hperson-province-select-ca').value = document.querySelector('#hperson-province-select-pa').value
        document.querySelector('#hperson-city-select-ca').value = document.querySelector('#hperson-city-select-pa').value
        document.querySelector('#hperson-home-phone-no-ca').value = document.querySelector('#hperson-home-phone-no-pa').value
        document.querySelector('#hperson-mobile-no-ca').value = document.querySelector('#hperson-mobile-no-pa').value
        document.querySelector('#hperson-email-ca').value = document.querySelector('#hperson-email-pa').value
    })
    let input_arr = ['#hperson-last-name' , '#hperson-first-name' , '#hperson-middle-name' , ' #hperson-birthday' , '#hperson-gender' , '#hperson-civil-status' , 
                        '#hperson-religion' , '#hperson-nationality' , '#hperson-phic' , '#hperson-house-no-pa' , '#hperson-street-block-pa' , '#hperson-region-select-pa' ,
                         '#hperson-region-select-pa' , '#hperson-province-select-pa' , '#hperson-city-select-pa' , '#hperson-brgy-select-pa' , '#hperson-mobile-no-pa',
                         '#hperson-house-no-ca' , '#hperson-street-block-ca' , '#hperson-region-select-ca' , '#hperson-region-select-ca' , '#hperson-province-select-ca' , 
                         '#hperson-city-select-ca' , '#hperson-brgy-select-ca' , '#hperson-mobile-no-ca']

    const checkInputFields = (inputs) => {
        for(let i = 0; i < input_arr.length; i++){
            if(inputs.val() === ""){
                inputs.removeClass('border-2 border-[#bfbfbf]')
                inputs.addClass('border-2 border-red-600')
            }
        }
    }

    $('#add-patform-btn-id').on('click' , function(event){
        event.preventDefault();

        // check if the required inputs have values , if no, border color = red.
        
        // $('#hperson-last-name').val()
        console.log(input_arr.length)
        for(let i = 0; i < input_arr.length; i++){
            checkInputFields($(input_arr[i]))
        }

        console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ' + $('#hperson-occupation').val())
        const data = {
            //PERSONAL INFORMATIONS
            //initial idea is to fetch the last patient hpatcode from the database whenever the patient registration form clicked
            //16
            hpercode : (Math.floor(Math.random() * 1000) + 1).toString(),
            hpatcode : (42801 + 1).toString(),
            patlast : $('#hperson-last-name').val(), // accepts null = no
            patfirst : $('#hperson-first-name').val(), //accepts null = no
            patmiddle : $('#hperson-middle-name').val(), // accepts null = yes
            patsuffix : ($('#hperson-ext-name').val()) ? $('#hperson-ext-name').val() : "N/A",
            pat_bdate : $('#hperson-birthday').val(), // accepts null = yes
            pat_age : $('#hperson-age').val(), // accepts null = yes
            patsex : $('#hperson-gender').val(), // accepts null = no
            patcstat : $('#hperson-civil-status').val(), //accepts null = yes
            relcode : $('#hperson-religion').val(), // accepts null = yes
            
            pat_occupation :($('#hperson-occupation').val()) ? $('#hperson-occupation').val() : "N/A",
            natcode : $('#hperson-nationality').val(), // accepts null = yes
            pat_passport_no : ($('#hperson-passport-no').val()) ? $('#hperson-passport-no').val() : "N/A",
            //SESSION the hospital code upon logging.
            hospital_code : ($('#hperson-hospital-no').val()) ? $('#hperson-hospital-no').val() : "N/A",
            phicnum : $('#hperson-phic').val(), // accepts null = yes

            //PERMANENT ADDRESS
            //9
            pat_bldg_pa : $('#hperson-house-no-pa').val(), // accepts null = yes
            hperson_street_block_pa: $('#hperson-street-block-pa').val(), // accepts null = yes
            pat_region_pa : $('#hperson-region-select-pa').val(), // accepts null = no
            pat_province_pa : $('#hperson-province-select-pa').val(), // accepts null = no
            pat_municipality_pa : $('#hperson-city-select-pa').val(), // accepts null = no
            pat_barangay_pa : $('#hperson-brgy-select-pa').val(), // accepts null = no
            pat_email_pa :($('#hperson-email-pa').val()) ? $('#hperson-email-pa').val() : "N/A",
            pat_homephone_no_pa : ($('#hperson-home-phone-no-pa').val()) ? $('#hperson-home-phone-no-pa').val() : "N/A",
            pat_mobile_no_pa : $('#hperson-mobile-no-pa').val(), // accepts null = no

            //CURRENT ADDRESS
            //9
            pat_bldg_ca : $('#hperson-house-no-ca').val(),
            hperson_street_block_ca: $('#hperson-street-block-ca').val(),
            pat_region_ca : $('#hperson-region-select-ca').val(),
            pat_province_ca : $('#hperson-province-select-ca').val(),
            pat_municipality_ca : $('#hperson-city-select-ca').val(),
            pat_barangay_ca : $('#hperson-brgy-select-ca').val(),
            pat_email_ca :($('#hperson-email-ca').val()) ? $('#hperson-email-ca').val() : "N/A",
            pat_homephone_no_ca : ($('#hperson-home-phone-no-ca').val()) ? $('#hperson-home-phone-no-ca').val() : "N/A",
            pat_mobile_no_ca : $('#hperson-mobile-no-ca').val(), // accepts null = no

            // CURRENT WORKPLACE ADDRESS
            //9
            pat_bldg_cwa : $('#hperson-house-no-cwa').val() ? $('#hperson-house-no-cwa').val() : "N/A",
            hperson_street_block_pa_cwa: $('#hperson-street-block-cwa').val() ? $('#hperson-street-block-cwa').val() : "N/A",
            pat_region_cwa : $('#hperson-region-select-cwa').val() ,
            pat_province_cwa : $('#hperson-province-select-cwa').val(),
            pat_municipality_cwa : $('#hperson-city-select-cwa').val(),
            pat_barangay_cwa : $('#hperson-brgy-select-cwa').val(),
            pat_namework_place : $('#hperson-workplace-cwa').val() ? $('#hperson-workplace-cwa').val() : "N/A",
            pat_landline_no : parseInt($('#hperson-ll-mb-no-cwa').val()) ? $('#hperson-ll-mb-no-cwa').val() : "N/A",
            pat_email_ca : $('#hperson-email-cwa').val() ? $('#hperson-email-cwa').val() : "N/A",


            // FOR OFW ONLY
            // 10
            pat_emp_name : $('#hperson-emp-name-ofw').val() ? $('#hperson-emp-name-ofw').val() : "N/A",
            pat_occupation_ofw: $('#hperson-occupation-ofw').val() ? $('#hperson-occupation-ofw').val() : "N/A",
            pat_place_work : $('#hperson-place-work-ofw').val()? $('#hperson-place-work-ofw').val() : "N/A",
            pat_bldg_ofw : $('#hperson-house-no-ofw').val() ? $('#hperson-house-no-ofw').val() : "N/A",
            hperson_street_block_ofw : $('#hperson-street-ofw').val() ? $('#hperson-street-ofw').val() : "N/A",
            pat_region_ofw : $('#hperson-region-select-ofw').val(),
            pat_province_ofw : $('#hperson-province-select-ofw').val(),
            pat_city_ofw : $('#hperson-city-select-ofw').val() ,
            // pat_email_ca : $('#hperson-country-select-ofw').val() ? $('#hperson-ountry-select-ofw').val() : "N/A",
            pat_office_mobile_no_ofw : parseInt($('#hperson-office-phone-no-ofw').val()) ? $('#hperson-office-phone-no-ofw').val() : 0,
            pat_mobile_no_ofw : parseInt($('#hperson-mobile-no-ofw').val()) ? $('#hperson-mobile-no-ofw').val() : 0,
        }

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
              console.log(key + " -> " + data[key] + " -> " + typeof data[key]);
           }
          }

        $.ajax({
            url: './php/add_patient_form.php',
            method: "POST",
            data:data,
            success: function(response){
                console.log(response)
            }
        })
    })
})



