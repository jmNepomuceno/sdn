$(document).ready(function(){
    // array that will hold the data that fetch from the database. 
    let data_arr = {} // structure
    // {hpercode : {time: 0 , func : run_timer},
    // {hpercode : { time: 0 , func : run_timer},
    // {hpercode : { time: 0 , func : run_timer}

    // data table varibles and data table functionalities
    $('#myDataTable').DataTable({
        "bSort": false
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    // Disable the search input 
    dataTable.search('').draw(); 

    // Disable the search button
    $('.dataTables_filter').addClass('hidden');

    let modal_filter = ''

    var table = $('#myDataTable').DataTable();
    var totalRecords = table.rows().count();

    //global variables
    let global_single_hpercode = "";
    let global_hpercode_all = document.querySelectorAll('.hpercode')
    let global_stopwatch_all = document.querySelectorAll('.stopwatch')
    let global_pat_status = document.querySelectorAll('.pat-status-incoming')

    let intervalIDs = {};
    let length_curr_table = document.querySelectorAll('.hpercode').length;
    let inactivityTimer;

    // ---------------------------------------------------------------------------------------------------------

    let userIsActive = true;
    function handleUserActivity() {
        userIsActive = true;
        // Additional code to handle user activity if needed
        // console.log('active')
    }

    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        // Additional code to handle user inactivity if needed
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            },
            success: function(response) {
                global_stopwatch_all = []
                global_hpercode_all = []

                populateTbody(response)

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
        });
    }

    // Attach event listeners
    document.addEventListener('mousemove', handleUserActivity);

    // Set up a timer to check user inactivity periodically
    const inactivityInterval = 52000; // Execute every 5 seconds (adjust as needed)

    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false; // Reset userIsActive after each check
            
        }, inactivityInterval);
    }

    function resetInactivityTimer() {
        clearInterval(inactivityTimer);
        startInactivityTimer();
    }

    // Start the inactivity timer when the page loads
    startInactivityTimer();

    //start - open modal 
    const ajax_method = (index, event) => {
        global_single_hpercode = document.querySelectorAll('.hpercode')[index].value
        const data = {
            hpercode: document.querySelectorAll('.hpercode')[index].value
        }
        $.ajax({
            url: './php/process_pending.php',
            method: "POST",
            data:data,
            success: function(response){
                response = JSON.parse(response); 
                console.log(response)
                pendingFunction(response)
            }
        })
        
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
    pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            console.log('den')
            ajax_method(index)
        });
    });

    //end - open modal 

    const pendingFunction = (response) =>{
        $('#pat-status-form').text(response[0].status)

        if(response[0].status === 'Pending'){
            $('#pat-status-form').addClass('text-gray-500')
            $('#pat-status-form').removeClass('text-cyan-500')
            $('#pat-status-form').removeClass('text-green-500')


            $('#status-bg-div').addClass('bg-gray-600')
            $('#status-bg-div').removeClass('bg-cyan-500')
            $('#status-bg-div').removeClass('bg-green-500')


            $('#approval-form').addClass('hidden')
            $('#arrival-form').addClass('hidden')
            $('#approval-details').addClass('hidden')
            $('#cancel-form').addClass('hidden')

            $('#pending-start-div').removeClass('hidden')

        }

        if(response[0].status === 'On-Process'){
            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')
            $('#pat-status-form').addClass('text-cyan-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')
            $('#status-bg-div').addClass('bg-cyan-500')

            $('#approval-form').removeClass('hidden')

            $('#arrival-form').addClass('hidden')
            $('#approval-details').addClass('hidden')
            $('#cancel-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')

        }

        if(response[0].status === 'Approved'){
            $('#temp-forward-form').addClass('hidden')

            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            
            $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')

            $('#arrival-form').removeClass('hidden')
            $('#approval-details').removeClass('hidden')
            $('#cancel-form').removeClass('hidden')
        }

        if(response[0].status === 'Arrived'){
            $('#temp-forward-form').addClass('hidden')

            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            // $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#arrival-form').addClass('hidden')
            $('#cancel-form').addClass('hidden')

            $('#checkup-form').removeClass('hidden')
            $('#arrival-details').removeClass('hidden')
            $('#approval-details').removeClass('hidden')
        }


        $('#pendingModal').removeClass('hidden')
        $('#refer-agency').text(" " + response[0].referred_by)
        $('#refer-reason').text(" " + response[0].reason_referral)
        $('#pending-type-lbl').text(response[0].type)
        $('#pending-name').text(" " + response[0].patlast + ", " + response[0].patfirst + " " + response[0].patmiddle)
        $('#pending-bday').text(" " + response[1].patbdate)
        $('#pending-age').text(" " + response[1].pat_age + " years old")
        $('#pending-sex').text(" " + response[1].patsex)
        $('#pending-civil').text(" " + response[1].patcstat)
        $('#pending-religion').text(" " + response[1].relcode)
        $('#pending-address').text(" " + response[1].pat_bldg + " " + response[1].pat_street_block + " " + response[1].pat_barangay + " " + response[1].pat_municipality + " " + response[1].pat_province + " " + response[1].pat_region)

        $('#pending-parent').text(" " + response[0].parent_guardian)
        $('#pending-phic').text(" " + (response[0].phic_member === 'true') ? " Yes" : "No")
        $('#pending-transport').text(" " + response[0].transport)
        $('#pending-admitted').text(" " + response[1].created_at)
        $('#pending-referring-doc').text(" " + response[0].referring_doctor)
        $('#pending-contact-no').text(" 0" + response[1].pat_mobile_no)

        if(response[0].type === 'OB'){
            $('#pending-ob').text(" " + response[1].created_at) // not yet
            $('#pending-last-mens').text(" " + response[0].referring_doctor) // not yet
            $('#pending-gestation').text(" " + response[1].pat_mobile_no) // not yet

            $('.pending-type-ob').removeClass('hidden') // not yet
            // Fetal Heart Tone:This is where you put the data
            // Fundal Height:This is where you put the data

            // Internal ExaminationThis is where you put the data
            // Cervical Dilatation:This is where you put the data
            // Bag of Water:This is where you put the data
            // Presentation:This is where you put the data
            // Others:This is where you put the data
        }else if(response[0].type === 'OPD'){
            $('.pending-type-ob').addClass('hidden') // not yet
        }
        else if(response[0].type === 'ER'){
            $('.pending-type-ob').addClass('hidden') // not yet
        }


        $('#pending-complaint-history').text(" " + response[0].chief_complaint_history)

        $('#pending-pe').text(" " + response[0].chief_complaint_history) // not yet
        $('#pending-bp').text(" " + response[0].bp)
        $('#pending-hr').text(" " + response[0].hr)
        $('#pending-rr').text(" " + response[0].rr)
        $('#pending-temp').text(" " + response[0].temp)
        $('#pending-weight').text(" " + response[0].weight)

        $('#pending-p-pe-find').text(" " + response[0].pertinent_findings)

        $('#pending-diagnosis').text(" " + response[0].diagnosis)
    }

    // populate the body
    const populateTbody = (response) =>{
        // console.log('tbody')    
        response = JSON.parse(response);
        let index = 0;
        let previous = 0;

         // get first the last value of the response , which is the new value upon fetching the data from the database.
         if(Object.entries(response).length > 1){
            let keysArray = Object.keys(response);
            let lastKey = keysArray[keysArray.length - 1];

            if (!data_arr.hasOwnProperty(response[lastKey]['hpercode'])) {
                data_arr[response[lastKey]['hpercode']] = {
                    time : null,
                    status : "",
                    time_logout: null,
                }
            }
         }
         
 
        

        
        // need to update the laman of all global variables on every populate of tbody.
        // update the global_hpercode_all based on the current laman of the table
        length_curr_table = response.length

        const incoming_tbody = document.querySelector('#incoming-tbody')
        // console.log(incoming_tbody.hasChildNodes())
        while (incoming_tbody.hasChildNodes()) {
            incoming_tbody.removeChild(incoming_tbody.firstChild);
        }
        // console.log(response.length)
        for(let i = 0; i < response.length; i++){
            
            if(previous == 0){
                index += 1;
            }else{
                if(response[i]['reference_num'] == previous){
                    index += 1;
                }else{
                    index = 1;
                }  
            }

            let type_color;
            if(response[i]['type'] == 'OPD'){
                type_color = 'bg-amber-600';
            }else if(response[i]['type'] == 'OB'){
                type_color = 'bg-green-500';
            }else if(response[i]['type'] == 'ER'){
                type_color = 'bg-sky-700';
            }

            const tr = document.createElement('tr')
            tr.className = 'h-[61px]'

            const td_name = document.createElement('td')
            td_name.textContent = response[i]['reference_num'] + " - " + index

            const td_reference_num = document.createElement('td')
            td_reference_num.textContent = response[i]['patlast'] + ", " + response[i]['patfirst'] + " " + response[i]['patmiddle']

            const td_type = document.createElement('td')
            td_type.textContent = response[i]['type']
            td_type.className = `h-full font-bold text-center ${type_color}`

            const td_referr = document.createElement('td')

            const td_referr_label = document.createElement('label')
            td_referr_label.textContent = "Referred: " + response[i]['referred_by']
            td_referr_label.className = `text-xs ml-1`

            const td_referr_div = document.createElement('div')
            td_referr_div.className = 'flex flex-row justify-start items-center'

            const td_referr_label_1 = document.createElement('label')
            td_referr_label_1.textContent = "Landline: " + response[i]['landline_no']
            td_referr_label_1.className = `text-[7.7pt] ml-1`

            const td_referr_label_2 = document.createElement('label')
            td_referr_label_2.textContent = "Mobile: " + response[i]['mobile_no']
            td_referr_label_2.className = `text-[7.7pt] ml-1`

            const td_time = document.createElement('td')

            const td_time_div_label_1 = document.createElement('label')
            td_time_div_label_1.textContent = " Referred: " + response[i]['date_time']
            td_time_div_label_1.className = `text-md`

            const td_time_div_label_2 = document.createElement('label')
            td_time_div_label_2.textContent = (response[i]['approved_time']) ?  " Processed: " + response[i]['approved_time'] : " Processed: 00:00:00"
            td_time_div_label_2.className = `text-md`

            if(response[i]['final_progressed_timer'] !== null){
                // Input time duration in "hh:mm:ss" format
                let timeString = response[i]['final_progressed_timer'];

                // Split the time string into hours, minutes, and seconds
                let [hours, minutes, seconds] = timeString.split(':').map(Number);

                // Calculate the total duration in milliseconds
                let totalMilliseconds = (hours * 60 * 60 + minutes * 60 + seconds) * 1000;

                // console.log(totalMilliseconds); // Output: 99000
            }


            const td_processing = document.createElement('td')

            const td_processing_div = document.createElement('div')
            td_processing_div.className = 'flex flex-row justify-around items-center'
            
            const td_processing_div_2 = document.createElement('div')

            global_stopwatch_all.push(td_processing_div_2)

            var timeString = td_processing_div_2.textContent; // Example time string in "hh:mm:ss" format
            var match = timeString.match(/(\d+):(\d+):(\d+)/);

            if (match) {
                var hours = parseInt(match[1], 10);
                var minutes = parseInt(match[2], 10);
                var seconds = parseInt(match[3], 10);

                var totalMinutes = hours * 60 + minutes + seconds / 60;
                // console.log(totalMinutes); // Output: 3.466666666666667
                if(totalMinutes > 0.05){ // to be change
                    td_processing_div_2.style.color = 'red'
                }
            }

            // td_processing_div_2.id = 'stopwatch'
            td_processing_div_2.className = 'stopwatch'

            const td_status = document.createElement('td')
            td_status.className = `font-bold text-center bg-gray-500`

            const td_status_div = document.createElement('div')
            td_status_div.className = `pat-status-incoming flex flex-row justify-around items-center`
            td_status_div.textContent = response[i]['status']

            const td_status_div_i = document.createElement('i')
            td_status_div_i.className = `pencil-btn fa-solid fa-pencil cursor-pointer hover:text-white`

            const td_status_div_input = document.createElement('input')
            td_status_div_input.className = `hpercode`
            td_status_div_input.type = "hidden";
            td_status_div_input.name = "hpercode";
            td_status_div_input.value = response[i]['hpercode'];  
            
            // update the global_hpercode_all based on the current laman of the table
            global_hpercode_all.push(td_status_div_input)

            td_status_div.appendChild(td_status_div_i)
            td_status_div.appendChild(td_status_div_input)
            td_status.appendChild(td_status_div)
            // end

            td_time.appendChild(td_time_div_label_1)
            td_time.appendChild(td_time_div_label_2)

            td_referr_div.appendChild(td_referr_label_1)
            td_referr_div.appendChild(td_referr_label_2)

            td_referr.appendChild(td_referr_label)
            td_referr.appendChild(td_referr_div)

            td_processing_div.appendChild(td_processing_div_2)

            td_processing.appendChild(td_processing_div)
            
            tr.appendChild(td_name)
            tr.appendChild(td_reference_num)
            tr.appendChild(td_type)
            tr.appendChild(td_referr)
            tr.appendChild(td_time)
            tr.appendChild(td_processing)
            tr.appendChild(td_status)

            document.querySelector('#incoming-tbody').appendChild(tr)

            previous = response[i]['reference_num'];

            
            // if(response[i].status === 'On-Process'){
            //     hpercode_with_timer_running.push({ 'hpercode' : response[i].hpercode})
            // }
        }
    }

    // MAIN BUTTON FUNCTIONALITIES - START - APPROVED - CLOSED - N
    $('#pending-start-btn').on('click' , function(event){
        $('#approval-form').removeClass('hidden')
        $('#pat-status-form').text('On-Process')

        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){     
                response = JSON.parse(response);           

                let hpercode_index = 0;
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    if( document.querySelectorAll('.hpercode')[i].value === global_single_hpercode){
                        hpercode_index = i;
                    }
                } 

                console.log(hpercode_index)
            }
        })

        // run_timers[pencil_index_clicked]['func'](pencil_index_clicked , "0" , pat_clicked_code);

        // starting the timer // current_time parameter = 0 it is for whenever there is a patient data processing
        data_arr[global_single_hpercode]['func'](global_single_hpercode , "0") // calling the run_timer function
        // {hpercode : {time: 0 , func : run_timer},
        // {hpercode : { time: 0 , func : run_timer},
        // {hpercode : { time: 0 , func : run_timer}
        
        let index_pat_status = 0
        for(let i = 0; i < global_pat_status.length; i++){
            if(global_hpercode_all[i].value === global_single_hpercode){
                index_pat_status = i
                break
            }
        }

        console.log("roflmao: " + index_pat_status)
        global_pat_status[index_pat_status].textContent = "On-Process"
        data_arr[global_single_hpercode].status = "On-Process"

    })

    $('#pending-approved-btn').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Approval Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'approval_confirmation'

        $('#myModal-incoming').modal('show');
    })

    $('#close-pending-modal').on('click' , function(event){
        $('#pendingModal').addClass('hidden')
    })

    // modal showing upon clicking the approval
    $('#yes-modal-btn-incoming').on('click' , function(event){
        // clear the timer
        if(modal_filter === 'approval_confirmation'){
            if (intervalIDs.hasOwnProperty(`interval_${global_single_hpercode}`)) {
                console.log('here')

                clearInterval(intervalIDs['interval_' + global_single_hpercode]);
                delete intervalIDs['interval_' + global_single_hpercode];
                // document.querySelectorAll('.pat-status-incoming')[pencil_index_clicked_temp].textContent = "Approved"
            }
            
            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Approved"

            const data = {
                global_single_hpercode : global_single_hpercode,
                timer : data_arr[global_single_hpercode].time,
                approve_details : $('#eraa').val(),
                case_category : $('#approve-classification-select').val(),
                action : $('#approved-action-select').val()
            }

            console.log(data);

            $.ajax({
                url: './php/approved_pending.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }

        else if(modal_filter === 'arrival_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                arrival_details : $('#arrival-text-area').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Arrived"

            $.ajax({
                url: './php/approved_to_arrival.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }
        else if(modal_filter === 'cancellation_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                cancel_details : $('#cancellation-textarea').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Cancelled"

            $.ajax({
                url: './php/approved_to_cancellation.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }
        else if(modal_filter === 'checked_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                checkup_classification_select : $('#checkup-classification-select').val(),
                checkup_textarea : $('#checkup-textarea').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Checked"

            $.ajax({
                url: './php/arrived_to_checked.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }
    })

    // incase of forwarding the patient
    $('#forward-continue-btn').on('click' , function(event){
        $('#temp-forward-form').addClass('hidden')
        $('#pat-forward-form').removeClass('hidden')
    })

    $('#forward-cancel-btn').on('click' , function(event){
        $('#temp-forward-form').removeClass('hidden')
        $('#pat-forward-form').addClass('hidden')
    })

    $('.pre-emp-text').on('click' , function(event){
        var originalString = event.target.textContent;
        // Using substring
        var stringWithoutPlus = originalString.substring(2);

        // Or using slice
        // var stringWithoutPlus = originalString.slice(2);
        $('#eraa').val($('#eraa').val() + " " + stringWithoutPlus  + " ")
    })

    $('#approved-action-select').change(function(){
        let selectedValue = $(this).val();
        // console.log($('#eraa').val())
        console.log($('#approve-classification-select').val())
        // Check if a value is selected
        if (selectedValue != "") {
            // alert("Selected value: " + selectedValue);
            $('#pending-approved-btn').removeClass('opacity-30 pointer-events-none')
        } else {
            $('#pending-start-btn').addClass('opacity-50 pointer-events-none')
        }
    })

    $('#arrival-submit').on('click' , function(){
        // console.log($('#arrival-text-area').val())

        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Arrival Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'arrival_confirmation'

        $('#myModal-incoming').modal('show');
    })

    $('#cancel-submit').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Cancellation Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'cancellation_confirmation'

        $('#myModal-incoming').modal('show');
    })

    $('#check-submit-btn').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Checked Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'checked_confirmation'

        $('#myModal-incoming').modal('show');
    })

    // END MAIN BUTTON FUNCTIONALITIES - START - APPROVED - CLOSED - N
 
    // SEARCHING FUNCTIONALITIES
    $('#incoming-search-btn').on('click' , function(event){        
        $('#incoming-clear-search-btn').removeClass('opacity-30 pointer-events-none')
        console.log(data_arr)
        let data = {
            get_all : false,
            ref_no : $('#incoming-referral-no-search').val(),
            last_name : $('#incoming-last-name-search').val(),
            first_name : $('#incoming-first-name-search').val(),
            middle_name : $('#incoming-middle-name-search').val(),
            case_type : $('#incoming-type-select').val(),
            agency : $('#incoming-agency-select').val(),
            status : $('#incoming-status-select').val()
        }

        // console.log(data)
            $.ajax({
                url: './php/outgoing_search.php',
                method: "POST", 
                data:data,
                success: function(response){
                    global_stopwatch_all = []
                    global_hpercode_all = []

                    clearInterval(inactivityTimer);

                    populateTbody(response)

                    const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                    });
                }
            })   
    })

    $('#incoming-clear-search-btn').on('click' , function(event){
        $.ajax({
            url: './php/fetch_interval.php',
            method: "POST",
            data:{
                from_where : 'incoming'
            },
            success: function(response){
                global_stopwatch_all = []
                global_hpercode_all = []
                populateTbody(response)
    
                response = JSON.parse(response);    
                console.log(response)

                startInactivityTimer()

                $('#incoming-referral-no-search').val("")
                $('#incoming-last-name-search').val("")
                $('#incoming-first-name-search').val("")
                $('#incoming-middle-name-search').val("")
                $('#incoming-type-select').val("")
                $('#incoming-agency-select').val("")
                $('#incoming-status-select').val('Pending')

                $('#incoming-clear-search-btn').addClass('opacity-30 pointer-events-none')

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                    });

            }
        })
    })

    // MA DRUP PRESCRIPTION
    // DG reference number doctors order // ORT 

    $(window).on('load' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })
    
})