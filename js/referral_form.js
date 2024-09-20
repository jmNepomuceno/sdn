$(document).ready(function(){
    const myModal = new bootstrap.Modal(document.getElementById('myModal-referral'));

    const loadContent = (url) => {
        $.ajax({
            url:url,
            success: function(response){
                // console.log(response)
                $('#container').html(response);
            }
        })
    }
    $('#submit-referral-btn-id').on('click' , function(event){
        var selectedValue = $('input[name="sensitive"]:checked').val();
        console.log(selectedValue)

        var sensitive_radios = document.querySelectorAll('input[name="sensitive"]');
        var sensitive_selected = true;

        for (var i = 0; i < sensitive_radios.length; i++) {
            if (sensitive_radios[i].checked) {
                sensitive_selected = false;
                break;
            }
        }

        // if(parseInt(pat_age_data) >= 18){
            
        // }
        
        if(sensitive_selected) {
            let data; 
            if(parseInt(pat_age_data) >= 18){
                data = {
                    type : $('#type-input').val(),
                    code : $('#code-input').val(),
    
                    refer_to : $('#refer-to-select').val(),
                    sensitive_case : $('input[name="sensitive_case"]:checked').val(),
                    phic_member : $('#phic-member-select').val(),
                    transport : $('#transport-select').val(),
                    referring_doc : $('#referring-doc-input').val(),
    
                    complaint_history_input : $('#complaint-history-input').val(),
                    reason_referral_input : $('#reason-referral-input').val(),
                    diagnosis : $('#diagnosis').val(),
                    remarks : $('#remarks').val(),
    
                    bp_input : $('#bp-input').val(),
                    hr_input : $('#hr-input').val(),
                    rr_input : $('#rr-input').val(),
                    temp_input : $('#temp-input').val(),
                    weight_input : $('#weight-input').val(),
                    pe_findings_input : $('#pe-findings-input').val(),
                }
            }else{
                data = {
                    type : $('#type-input').val(),
                    code : $('#code-input').val(),
    
                    refer_to : $('#refer-to-select').val(),
                    sensitive_case : $('input[name="sensitive_case"]:checked').val(),
                    parent_guardian : $('#parent-guard-input').val(),
                    phic_member : $('#phic-member-select').val(),
                    transport : $('#transport-select').val(),
                    referring_doc : $('#referring-doc-input').val(),
    
                    complaint_history_input : $('#complaint-history-input').val(),
                    reason_referral_input : $('#reason-referral-input').val(),
                    diagnosis : $('#diagnosis').val(),
                    remarks : $('#remarks').val(),
    
                    bp_input : $('#bp-input').val(),
                    hr_input : $('#hr-input').val(),
                    rr_input : $('#rr-input').val(),
                    temp_input : $('#temp-input').val(),
                    weight_input : $('#weight-input').val(),
                    pe_findings_input : $('#pe-findings-input').val(),
                }
            }
            

            // console.log(pat_age_data, typeof pat_age_data)
            // if(parseInt(pat_age_data) >= 18){
            //     delete data.parent_guardian
            //     console.log(data)
            // }

            if($('#type-input').val() === "OB"){
                data['fetal_heart_inp'] = $('#fetal-heart-inp').val()
                data['fundal_height_inp'] = $('#fundal-height-inp').val()
                data['cervical_dilation_inp'] = $('#cervical-dilation-inp').val()
                data['bag_water_inp'] = $('#bag-water-inp').val()
                data['presentation_ob_inp'] = $('#presentation-ob-inp').val()
                data['others_ob_inp'] = $('#others-ob-inp').val()
            }

            function areAllValuesFilled(obj) {
                for (const key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        if (typeof obj[key] === 'string' && !obj[key].trim()) {
                            return false;
                        }
                    }
                }
                return true;
            }

            

            console.log(data)
            if (areAllValuesFilled(data)) {
                data['parent_guardian'] = $('#parent-guard-input').val() ? $('#parent-guard-input').val() : "N/A"
                console.log(data)
                $.ajax({
                    url: '../SDN/add_referral_form.php',
                    method: "POST",
                    data:data,
                    success: function(response){
                        // response = JSON.parse(response); 
                        console.log(response)

                        $('#modal-title').text('Successed')
                        $('#modal-icon').removeClass('fa-triangle-exclamation')
                        $('#modal-icon').addClass('fa-circle-check')
                        $('#modal-body').text('Successfully Referred')
    
                        $('#yes-modal-btn').css('display' , 'none')
                        $('#ok-modal-btn').text('OK')
                        // $('#myModal').modal('show');
                        
                        
                        $('#ok-modal-btn').on('click' , function(event){
                            if($('#ok-modal-btn').text() == 'OK'){
                                loadContent('../SDN/default_view2.php')
                            }
                        })
                    }
                })
            } else {
                myModal.show();
                console.log('invalid')
            }

            
        }
        
    })

    $('#cancel-referral-btn-id').on('click' , function(){
        $('#modal-title').text('Warning')
        $('#modal-icon').attr('class', 'fa-solid fa-triangle-exclamation');
        $('#modal-body').text('Are you sure you want to cancel the referral?')
        $('#ok-modal-btn').text('No')

        $('#yes-modal-btn').css('display' , "block")
    })

    $('#yes-modal-btn').on('click' , () =>{
        if($('#yes-modal-btn').text() === 'Yes'){
            // window.location.href = "http://10.10.90.14:8079/index.php" 
            loadContent('../SDN/patient_register_form2.php')
        }
    })
})