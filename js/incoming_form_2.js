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

    // ---------------------------------------------------------------------------------------------------------

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
                pendingFunction(response)
            }
        })
        
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
    pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            ajax_method(index)
        });
    });

    //end - open modal 

    const pendingFunction = (response) =>{
        $('#pendingModal').removeClass('hidden')
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
        console.log('tbody')
        response = JSON.parse(response);
        let index = 0;
        let previous = 0;
        console.log(response)

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
            td_time_div_label_2.textContent = " Processed: " + response[i]['final_progressed_timer']
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
            // td_processing.textContent = "Processing: " // from 1 to 4

            const td_processing_div = document.createElement('div')
            td_processing_div.className = 'flex flex-row justify-around items-center'
            td_processing_div.textContent = "Processing: "
            const td_processing_div_2 = document.createElement('div')
            
            if (data_arr[response[i]['hpercode']].status === 'On-Process') {
                // if it shows the patient who has currently processing, we are going to delete first the timer, then run again the timer 
                // upon sorting, whenever it shows or disappears, we are going to delete and re run the timer, to show the current timer
                console.log('kyla')
                clearInterval(intervalIDs['interval_' + response[i]['hpercode']]);
                delete intervalIDs['interval_' + response[i]['hpercode']];

                // continue
                data_arr[global_single_hpercode]['func'](response[i]['hpercode'] , data_arr[response[i]['hpercode']].time) // calling the run_timer function
            }
            
            // need to update the laman of all global variables on every populate of tbody.
            // update the global_hpercode_all based on the current laman of the table
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

        global_pat_status[index_pat_status].textContent = "On-Process"

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
                timer : data_arr[global_single_hpercode].time
            }

            console.log(data);

            $.ajax({
                url: './php/approved_pending.php',
                method: "POST",
                data : data,
                success: function(response){     
                    // response = JSON.parse(response);    
                    console.log(response)        

                    // $('#pendingModal').addClass('hidden')
                    // location.reload();
                }
             })
        }
    })

    // END MAIN BUTTON FUNCTIONALITIES - START - APPROVED - CLOSED - N

    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }

    const run_timer = (global_single_hpercode, current_time) =>{
        let startTime = 0; 
        let elapsedTime = 0;

        function formatTime(milliseconds) {
            const date = new Date(milliseconds);
            return date.toISOString().substr(11, 8);
        }

        if(current_time !== "0"){ // if it is from after the reload
            startTime =  parseTimeToMilliseconds(current_time);
        }else{ // for a new patient processing process :D 
            startTime = new Date().getTime() - elapsedTime;
        }

      
        // const uniqueIdentifier = `interval_${index}`;

        const uniqueIdentifier = `interval_${global_single_hpercode}`;
        let data;

        // console.log(data_arr)

        intervalIDs[uniqueIdentifier] = setInterval(() => {
            if(current_time === "0"){
                const currentTime = new Date().getTime();
                elapsedTime = currentTime - startTime;

                // find the current index of the clicked hpercode based on the current data in the table
                let index_hpercode = 0;
                for(let i = 0; i < length_curr_table; i++){
                    if(global_single_hpercode === global_hpercode_all[i].value){
                        index_hpercode = i;
                    }
                }

                // printing the formatTime
                global_stopwatch_all[index_hpercode].textContent = formatTime(elapsedTime)

                // changing the color of the text based on the 'matagal ma process'
                if(elapsedTime >= 5000){
                    global_stopwatch_all[index_hpercode].style.color = "red"
                }

                data = {
                    // timer_running : true,
                    global_single_hpercode : global_single_hpercode,
                    elapsedTime : formatTime(elapsedTime),
                    table_index : index_hpercode,
                    // approved_bool : approved_clicked_bool,
                    // approved_clicked_hpercode : approved_clicked_hpercode, 
                    // secs_add : secs_add
                }

                data_arr[global_single_hpercode].time = formatTime(elapsedTime)
                // console.log(data_arr)
            }else{
                
                startTime += 1000

                // find the current index of the clicked hpercode based on the current data in the table
                let index_hpercode;
                for(let i = 0; i < length_curr_table; i++){
                    // console.log(global_hpercode_all[i].value , global_single_hpercode)
                    if(global_single_hpercode === global_hpercode_all[i].value){
                        index_hpercode = i;
                    }
                }
                
                // condition mo dapat pag wala value si index_hpercode, wala dapat mangyayare or di dapat mag r run yung number 486
                // printing the formatTime
                global_stopwatch_all[index_hpercode].textContent = formatTime(startTime)

                // changing the color of the text based on the 'matagal ma process'
                if(startTime >= 5000){
                    global_stopwatch_all[index_hpercode].style.color = "red"
                }

                data_arr[global_single_hpercode].time = formatTime(startTime)

                data = {
                    // timer_running : true,
                    global_single_hpercode : global_single_hpercode,
                    elapsedTime : formatTime(startTime),
                    table_index : index_hpercode,
                    // approved_bool : approved_clicked_bool,
                    // approved_clicked_hpercode : approved_clicked_hpercode, 
                    // secs_add : secs_add
                }   

                console.log(data_arr)

            }
            
            
            // console.log(data)
            $.ajax({
                url: './php/process_timer_2.php',
                method: "POST",
                data:data,
                success: function(response){
                    // console.log(response)
                    response = JSON.parse(response);    
                    // console.log(response)            
                }
            })
        }, 1000)
    }


    // initialize the structure of the table_arr based on the data that have been fetched from the database // fetch the status ?
    $.ajax({
        url: './php/fetch_status.php',
        method: "POST",
        success: function(response){
            response = JSON.parse(response);  

            for(let i = 0; i < response.length; i++){
                try {
                    // Your code that may cause an error
                    data_arr[response[i].hpercode] = { time: response[i].response_time, status:response[i].status, time_logout: response[i].progress_timer,  func: run_timer };
                } catch (error) {
                    console.error("Error:", error);
                
                    // You can also add more specific handling based on the type of error
                    if (error instanceof TypeError) {
                        // Handle TypeError
                        console.error("This is a TypeError. Check the type and value of global_hpercode_all[i].value");
                    } else {
                        // Handle other types of errors
                        console.error("An unexpected error occurred. Check the console for details.");
                    }
                }
            }

            // check if the length of session process_timer is > 1, this is for whenever the there is a timer running and the user refresh the page
            let after_reload = []
            if($('#timer-running-input').val() === '1' && $('#post-value-reload-input').val() !== '1'){ // if global_process_timer_running === 1
                $.ajax({
                    url: './php/fetch_onProcess.php',
                    method: "POST",
                    success: function(response){               
                        response = JSON.parse(response);
                        after_reload = response
                        console.log(after_reload)

                        for(let i = 0; i < response.length; i++){
                            global_single_hpercode = after_reload[i].global_single_hpercode
                            data_arr[after_reload[i].global_single_hpercode]['func'](global_single_hpercode, after_reload[i].elapsedTime)    
                        }
                    }
                })
            }

            // after reload, the exisiting processing timer are still running after logout and logging in.
            if($('#post-value-reload-input').val() === 'true' && $('#timer-running-input').val() !== '1' ){
                console.log("after logout")

                $.ajax({
                    url: './php/save_process_time.php',
                    method: "POST",
                    data : {what: 'continue'},
                    success: function(response){
                        response = JSON.parse(response);  
                        console.log(response)
                        for(let i = 0; i <response.length; i++){
                            data_arr[response[i].hpercode]['func'](response[i].hpercode , response[i].progress_timer)
                        }
                    }
                })
            }
        }
    })


    // SEARCHING FUNCTIONALITIES
    $('#incoming-search-btn').on('click' , function(event){        
        $('#incoming-clear-search-btn').removeClass('opacity-30 pointer-events-none')
 
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
        if(data.ref_no === "" && data.last_name === "" && data.first_name === "" && data.middle_name === "" && data.case_type === "" && data.agency === "" && data.status === 'Pending'){
            $('#modal-title-incoming').text('Warning')
            $('#modal-icon').addClass('fa-triangle-exclamation')
            $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body-incoming').text('Fill at least one bar.')
            $('#ok-modal-btn-incoming').text('Ok')

            $('#myModal-incoming').modal('show');
            
        }else{
            console.log(data)
            $.ajax({
                url: './php/incoming_search.php',
                method: "POST", 
                data:data,
                success: function(response){
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
            })
        }
        
        
    })

    // MA DRUP PRESCRIPTION
    // DG reference number doctors order // ORT 
})