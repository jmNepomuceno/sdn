// const nav_mobile_acc_div = document.querySelector('#nav-mobile-account-div');
// const nav_account_div = document.querySelector('#nav-account-div');
// const close_nav_mobile_btn = document.querySelector('#close-nav-mobile-btn')
// const nav_drop_account_div = document.querySelector('#nav-drop-account-div')

// nav_account_div.addEventListener('click', () => {
//     if(nav_drop_account_div.classList.contains("hidden")){
//         nav_drop_account_div.classList.remove('hidden')
//     }else{
//         nav_drop_account_div.classList.add('hidden')
//     }
//     nav_mobile_acc_div.classList.toggle('translate-x-32');
// });

// close_nav_mobile_btn.addEventListener('click', () => {
//     nav_mobile_acc_div.classList.toggle('translate-x-32');
// });

///////////////////////////////////////////////
const side_bar_div = document.querySelector('#side-bar-div');
const side_bar_mobile_btn = document.querySelector('#side-bar-mobile-btn');
const patient_reg_form_div_1 = document.querySelector('#patient-reg-form-div-1')
const patient_reg_form_div_2 = document.querySelector('#patient-reg-form-div-2')
const patient_reg_form_div_3 = document.querySelector('#patient-reg-form-div-3')
const license_div = document.querySelector('#license-div')

$(document).ready(function(){
    $('#side-bar-mobile-btn').on('click' , function(event){
        event.preventDefault();
        document.querySelector('#side-bar-div').classList.toggle('-ml-[325px]');

        //document.querySelector('#patient-reg-form-div-1').classList.toggle('w-full');
        // console.log("asdf")

        if(document.querySelector('#patient-reg-form-div-1')){
            if(document.querySelector('#patient-reg-form-div-1').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-2').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-3').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[25%]')
            }
        }
        
        
        if(document.querySelector('#license-div')){
            if(document.querySelector('#license-div').classList.contains('w-full')){
                document.querySelector('#license-div').classList.add('w-[80%]')
                document.querySelector('#license-div').classList.remove('w-full')
            }else{
                document.querySelector('#license-div').classList.remove('w-[80%]')
                document.querySelector('#license-div').classList.add('w-full')
            }
        }

        
    })
})

// side_bar_mobile_btn.addEventListener('click', () => {
    // if(side_bar_div.classList.contains("-translate-x-64")){

    // }
    // side_bar_div.classList.toggle('-ml-[325px]');
    //patient_reg_form_div.classList.toggle('w-full')

    // if(patient_reg_form_div_1.classList.contains('w-[30%]')){
    //     patient_reg_form_div_1.classList.add('w-[25%]')
    //     patient_reg_form_div_1.classList.remove('w-[30%]')
    // }else{
    //     patient_reg_form_div_1.classList.add('w-[30%]')
    //     patient_reg_form_div_1.classList.remove('w-[25%]')
    // }

    // if(patient_reg_form_div_2.classList.contains('w-[30%]')){
    //     patient_reg_form_div_2.classList.add('w-[25%]')
    //     patient_reg_form_div_2.classList.remove('w-[30%]')
    // }else{
    //     patient_reg_form_div_2.classList.add('w-[30%]')
    //     patient_reg_form_div_2.classList.remove('w-[25%]')
    // }

    // if(patient_reg_form_div_3.classList.contains('w-[30%]')){
    //     patient_reg_form_div_3.classList.add('w-[25%]')
    //     patient_reg_form_div_3.classList.remove('w-[30%]')
    // }else{
    //     patient_reg_form_div_3.classList.add('w-[30%]')
    //     patient_reg_form_div_3.classList.remove('w-[25%]')
    // }
    // console.log("herte")
    // if(license_div.classList.contains('w-full')){
    //     license_div.classList.add('w-[80%]')
    //     license_div.classList.remove('w-full')
    // }else{
    //     license_div.classList.remove('w-[80%]')
    //     license_div.classList.add('w-full')
    // }

   
    
    // nav_mobile_acc_div.classList.toggle('translate-x-32');
// });


/////////////////////////////////////////
// const main_side_bar_1 = document.querySelector('#main-side-bar-1')
// const sub_side_bar_1 = document.querySelector('#sub-side-bar-1')
// const main_side_bar_2 = document.querySelector('#main-side-bar-2')
// const sub_side_bar_2 = document.querySelector('#sub-side-bar-2')

// const toggleSideBars = (sub) => {
//     console.log("here")

//     if(sub.classList.contains('hidden')){
//         sub.classList.add('block')
//         sub.classList.remove('hidden')
//     }
//     else if(sub.classList.contains('block')){
//         sub.classList.add('hidden')
//         sub.classList.remove('block')
//     }
// }

// main_side_bar_1.addEventListener('click', () => toggleSideBars(sub_side_bar_1), false);
// main_side_bar_2.addEventListener('click', () => toggleSideBars(sub_side_bar_2), false);


///////////////////////////////////
// const patient_reg_form_div = document.querySelector('#patient-reg-form-div');
// const patient_reg_form_side_sub_div = document.querySelector('#patient-reg-form-sub-side-bar')
// const default_div = document.querySelector('#default-div')
// const sdn_title_h1 = document.querySelector('#sdn-title-h1')

// patient_reg_form_side_sub_div.addEventListener('click' , function(){
//     patient_reg_form_div.classList.remove('hidden')
//     default_div.classList.add('hidden')
// })

// sdn_title_h1.addEventListener('click' , function(){
//     patient_reg_form_div.classList.add('hidden')
//     default_div.classList.remove('hidden')
// })


///////////////////////////////////////////////////
// traverse 
 


const loadContent = (url) => {
    $.ajax({
        url:url,
        success: function(response){
            // console.log(response)
            $('#container').html(response);
        }
    })
}
loadContent('php/patient_register_form.php')
$(document).ready(function(){
    // $(window).on('load' , function(event){
    //     event.preventDefault();
    //     // loadContent('php/default_view.php')
    //     // loadContent('php/patient_register_form.php')
    //     loadContent('php/opd_referral_form.php')

    // })

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        loadContent('php/default_view.php')
    })
})

$(document).ready(function(){
    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();
        // document.querySelector('#default-div').classList.add('hidden')
        loadContent('php/outgoing_form.php')
    })
})

$(document).ready(function(){
    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();
        // document.querySelector('#default-div').classList.add('hidden')
        loadContent('php/patient_register_form.php')
    })
})

$(document).ready(function(){
    $('#pcr-request-id').on('click' , function(event){
        event.preventDefault();
        // document.querySelector('#default-div').classList.add('hidden')
        loadContent('php/opd_referral_form.php')
    })
})

// document.querySelector('#asdf-div').style.color = 'red'
// console.log(document.querySelector('#asdf-div'))

