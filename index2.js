$(document).ready(function(){
    $('#query-signin-txt').on('click' , function(event){
        event.preventDefault();
        $('.main-content').css('display', 'none');
        $('.sub-content').css('display', 'flex');
    })

    // registration-btn
    $('#registration-btn').on('click' , function(event){
        event.preventDefault();

        $('.sub-content-registration-form').css('display', 'block');
        $('.sub-content-authorization-form').css('display', 'none');

        $('#registration-btn').attr('class', 'btn btn-primary');
        $('#authorization-btn').attr('class', 'btn btn-dark');

    })

    $('#authorization-btn').on('click' , function(event){
        event.preventDefault();

        $('.sub-content-registration-form').css('display', 'none');
        $('.sub-content-authorization-form').css('display', 'block');

        $('#registration-btn').attr('class', 'btn btn-dark');
        $('#authorization-btn').attr('class', 'btn btn-primary');
    })

    $("#sdn-landline-no").on("input", function(){
        let value = $("#sdn-landline-no").val().replace(/[^0-9]/g, '');
        // Add dashes at specific positions
        if (value.length >= 3) {
            value = value.slice(0, 3) + '-' + value.slice(3);
        }
        if (value.length > 8) {
            value = value.slice(0, 8);
        }
        $("#sdn-landline-no").val(value);
    })

    const mobileNumValue = (val) => {
        // Remove any non-numeric characters
        let value;
        if(val === 1){
            value = $("#sdn-hospital-mobile-no").val().replace(/[^0-9]/g, '');
        }else if(val === 2){
            value = $("#sdn-hospital-director-mobile-no").val().replace(/[^0-9]/g, '');
        }else if(val === 3){
            value = $("#sdn-point-person-mobile-no").val().replace(/[^0-9]/g, '');
        }
        // Add dashes at specific positions
        if (value.length >= 4) {
            value = value.slice(0, 4) + '-' + value.slice(4);
          }
          if (value.length >= 9) {
            value = value.slice(0, 9) + '-' + value.slice(9);
          }
          if (value.length > 13) {
            value = value.slice(0, 13);
          }
          if(val === 1){
            $("#sdn-hospital-mobile-no").val(value);
        }else if(val === 2){
            $("#sdn-hospital-director-mobile-no").val(value);
        }else if(val === 3){
            $("#sdn-point-person-mobile-no").val(value);
        }
    }

    $("#sdn-hospital-mobile-no").on("input", () => mobileNumValue(1))
    $("#sdn-hospital-director-mobile-no").on("input", () => mobileNumValue(2))
    $("#sdn-point-person-mobile-no").on("input", () => mobileNumValue(3))
})