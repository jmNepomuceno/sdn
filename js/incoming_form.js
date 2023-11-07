$(document).ready(function(){
    var search_clicked = false;
    // console.log($('#current-page-input').val())

    // data table functionalities
    $('#myDataTable').DataTable({
        "bSort": false
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    // Disable the search input 
    dataTable.search('').draw();

    // Disable the search button
    $('.dataTables_filter').addClass('hidden');

    var table = $('#myDataTable').DataTable();
    var totalRecords = table.rows().count();

    let pencil_index_clicked = 0;
    let pencil_index_clicked_temp = 0;
    let pat_clicked_code = 0
    let pat_clicked_code_temp = 0;

    var search_clicked = false;
    let processing_time;
    let processing_time_running = false;
    let incoming_time;
    // Set the time threshold for considering the user as idle (e.g., 5 minutes)
    // var idleTime = 5*60*1000; // 1 minutes in milliseconds
    var idleTime = 3000; // 3sec in milliseconds

    // Initialize a timer variable
    var idleTimer;
    let global_realtime_update; 
    let global_timer_continue;
    let global_hpercode;

    // Add event listeners for user activity
    document.addEventListener("mousemove", resetIdleTimer);
    document.addEventListener("keydown", resetIdleTimer);

    //CHECK IF THERE IS PREVIOUS TIMER THAT CURRENTLY RUNNING BEFORE LOGGING OUT
    let prev_running_timer_before_logout = []

    for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
        if(document.querySelectorAll('.pat-status-incoming')[i].textContent === " On-Process "){
            prev_running_timer_before_logout.push(i)
        }
    }

    $.ajax({
        url: './php/save_process_time.php',
        method: "POST",
        data : {what: 'continue'},
        success: function(response){
            response = JSON.parse(response);  
            console.log(response)

            for(let i = 0; i < prev_running_timer_before_logout.length; i++){
                try_arr[i]['func'](prev_running_timer_before_logout[i] , response[i].progress_timer , response[i].hpercode);
            }

            // PRINT MO NA LANG BUKAS YUNG VALUE GL HF TOMORROW! :))))((((())))))(((())))

        }
    })

    

    if($('#current-page-input').val() !== "incoming_page"){
        // console.log('asdf')
        processing_time_running = true
    }

    // Function to reset the idle timer
    function resetIdleTimer() {
        // console.log('reset idle timer')
        // console.log(processing_time_running)
        
        if(processing_time_running === false){
            // console.log('pota')
            clearTimeout(incoming_time)
            clearTimeout(idleTimer); // Clear the previous timer
            idleTimer = setTimeout(userIsIdle, idleTime); // Set a new timer
        }
    }

    // Function to be called when the user is considered idle
    function userIsIdle() {
        // console.log("User is idle. You can perform idle actions here.");

        if(processing_time_running === false){
            // console.log('here')
            fetchMySQLData_incoming(); 
        }
    }
    
    // console.log($('#timer-running-input').val())
    if($('#timer-running-input').val() === '1'){   
        console.log("here")
        // console.log(true)
        processing_time_running = true;
        const data = {
            timer_running : true,
        }

        $.ajax({
            url: './php/continue_process_timer.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)

                response = JSON.parse(response);
                console.log(response)

                global_realtime_update = response
                

                // console.log(document.querySelectorAll('.hpercode')[index].value)

                const stopwatchDisplay = document.querySelectorAll('.stopwatch');
                // for(let i = 0; i < response.length; i++){
                //     stopwatchDisplay[response[i]['table']].textContent = formatTime(elapsedTime) 
                // }

                let continue_timer_arr = []
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    for(let j = 0; j < response.length; j++){
                        if(response[j]['pat_clicked_code'] === document.querySelectorAll('.hpercode')[i].value){
                            continue_timer_arr.push(i)
                        }
                    }
                }

                global_timer_continue = continue_timer_arr

                // console.log('index: ' + continue_timer_arr)
                // print the value then fixed the other bug
                // console.log('yawa')
                console.log(response)
                for(let i = 0; i < continue_timer_arr.length; i++){
                    stopwatchDisplay[continue_timer_arr[i]].textContent = response[i].elapsedTime
                }

            }
        })
    }
    

    const ajax_method = (index, event) => {
        // console.log('okay')
        // console.log(document.querySelectorAll('.hpercode')[index].value)

        // if(processing_time_running === true){
        //     index -= 1
        // }

        pencil_index_clicked_temp = index
        // pat_clicked_code = document.querySelectorAll('.hpercode')[index].value
        pat_clicked_code_temp = document.querySelectorAll('.hpercode')[index].value

        // console.log(pat_clicked_code)

        global_hpercode = document.querySelectorAll('.hpercode')[index].value

        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        //     console.log(document.querySelectorAll('.hpercode')[i].value)
        // }

        const data = {
            status : 'Approved',
            hpercode: document.querySelectorAll('.hpercode')[index].value
        }

        console.log(data)
        $.ajax({
            url: './php/process_pending.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)
                response = JSON.parse(response);
                // console.log(response)   
                pendingFunction(response)
            }
        })
        
    }

    for(let i = 0; i < $('.pencil-btn').length; i++){
        document.querySelectorAll('.pencil-btn')[i].addEventListener('click', () => ajax_method(i))
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
    // console.log(pencil_elements.length)
    pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            ajax_method(index)
        });
    });

    // for(let i = 0; i < totalRecords; i++){
    //     console.log(pencil_elements[i])
    // }

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

    const populateTbody = (response) =>{
        console.log('tbody' , processing_time_running)
        // console.log("plain: " + response)
        response = JSON.parse(response);
        global_response = response
        let index = 0;
        let previous = 0;
        // console.log("json: " + response)
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
            td_time_div_label_1.textContent = "Referred: " + response[i]['date_time']
            td_time_div_label_1.className = `text-xs`

            const td_time_div_label_2 = document.createElement('label')
            td_time_div_label_2.textContent = "Processed: "
            td_time_div_label_2.className = `text-xs`


            const td_processing = document.createElement('td')
            // td_processing.textContent = "Processing: "

            const td_processing_div = document.createElement('div')
            td_processing_div.className = 'flex flex-row justify-around items-center'
            td_processing_div.textContent = "Processing: "
            const td_processing_div_2 = document.createElement('div')

            td_processing_div_2.textContent = "00:00:00"
            // td_processing_div_2.id = 'stopwatch'
            td_processing_div_2.className = 'stopwatch'

            // <td class="border-2 border-black">
            //     <div class="flex flex-row justify-around items-center">
            //         Processing: 
            //         <div> 
            //             <div id="stopwatch">00:00:00</div>
                        
            //         </div>
            //     </div>
            // </td>

            //start

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

        }

        if(global_timer_continue){
            for(let i = 0; i  < global_timer_continue.length; i++){
                // console.log('mags')
                console.log(global_realtime_update[i].elapsedTime)
                document.querySelectorAll('.stopwatch')[global_timer_continue[i]].textContent = global_realtime_update[i].elapsedTime
                // document.querySelectorAll('.stopwatch')[global_timer_continue[i]].textContent = "00:00:17"
            }
        }
        
        const ajax_method = (index) => {
            console.log('hgere')
            // pencil_index_clicked = index
            pencil_index_clicked_temp = index
            pat_clicked_code_temp = document.querySelectorAll('.hpercode')[index].value
            const data = {
                status : 'Approved',
                hpercode: document.querySelectorAll('.hpercode')[index].value
            }
    
            $.ajax({
                url: './php/process_pending.php',
                method: "POST",
                data:data,
                success: function(response){
                    response = JSON.parse(response);
                    // console.log(response)   
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

        if(processing_time_running === false){
        incoming_time = setTimeout(fetchMySQLData_incoming, 5000);

        }
    }

    function fetchMySQLData_incoming() {
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            },
            success: function(response) {
                populateTbody(response)
            }
        });
        
    }

    // SEARCH BAR

    //incoming-search-btn
    $('#incoming-search-btn').on('click' , function(event){
        // console.log(processing_time_running)
        processing_time_running = true;
        // console.log(processing_time_running)
        // clearTimeout(incoming_time)
        // clearTimeout(idleTimer);
        
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
        // console.log(data.middle_name)
        if(data.ref_no === "" && data.last_name === "" && data.first_name === "" && data.middle_name === "" && data.case_type === "" && data.agency === ""){
            $('#modal-title').text('Warning')
            $('#modal-icon').addClass('fa-triangle-exclamation')
            $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body').text('Fill at least one bar.')
            $('#ok-modal-btn').text('Ok')

            $('#myModal').modal('show');
        }else{
            $.ajax({
                url: './php/incoming_search.php',
                method: "POST",
                data:data,
                success: function(response){
                    // console.log(response)
                    
                    populateTbody(response)
    
                }
            })
        }
    })

    $('#incoming-clear-search-btn').on('click' , function(event){
        // console.log(processing_time_running)
        processing_time_running = false;
        // console.log(processing_time_running)
        // clearTimeout(incoming_time)
        // clearTimeout(idleTimer);
        const data = {
            get_all : true
        }

        $.ajax({
            url: './php/incoming_search.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)
                
                populateTbody(response)

            }
        })
    })

    //$('#pendingModal').removeClass('hidden')
    $('#close-pending-modal').on('click' , function(event){
        $('#pendingModal').addClass('hidden')
    })

    $('#yes-modal-btn').on('click' , function(event){
        console.log('here')
        // $.ajax({
        //     url: 'php/logout.php',
        //     success: function(data) {
        //         window.location.href = "./index.php" 
        //     }
        // });
    })

    let intervalIDs = {};

    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }


    const try_timer = (index , timeVar, after_reload) =>{ // after reload  = hpercode or pat_clicked_code
        console.log(index, timeVar)
        // console.log(pat_clicked_code)
        const stopwatchDisplay = document.querySelectorAll('.stopwatch');
        let startTime = 0; 
        let elapsedTime = 0;

        function formatTime(milliseconds) {
            const date = new Date(milliseconds);
            return date.toISOString().substr(11, 8);
        }

        if(timeVar !== "0"){
            startTime =  parseTimeToMilliseconds(timeVar);
        }else{
            startTime = new Date().getTime() - elapsedTime;
        }
        // console.log(startTime)
        // startTime = new Date().getTime() - elapsedTime;

        const uniqueIdentifier = `interval_${index}`;

        intervalIDs[uniqueIdentifier] = setInterval(() => {
            
            let data;
            if(timeVar === "0"){
                console.log('pisti')
                const currentTime = new Date().getTime();
                elapsedTime = currentTime - startTime;
                // console.log(elapsedTime)
                try_arr[index].time += 1;
                // console.log(formatTime(elapsedTime))
                stopwatchDisplay[index].textContent = formatTime(elapsedTime)

                if(elapsedTime >= 5000){
                    stopwatchDisplay[index].style.color = "red"
                }
                data = {
                    timer_running : false,
                    pat_clicked_code : after_reload,
                    elapsedTime : formatTime(elapsedTime),
                    table_index : index
    
                }
            }else{
                // console.log('ysaew')
                try_arr[index].time += 1;
                startTime += 1000
                // console.log(startTime)
                // console.log(formatTime(startTime))
                stopwatchDisplay[index].textContent = formatTime(startTime)
                
                //120000 = 2 mins
                if(startTime >= 5000){
                    stopwatchDisplay[index].style.color = "red"
                }

                // console.log('patcode: ' + after_reload, ' --- index: ' + index)
                data = {
                    timer_running : true,
                    pat_clicked_code : after_reload,
                    elapsedTime : formatTime(startTime),
                    table_index : index

                }
            }

            // console.log(data)  
            $.ajax({
               url: './php/process_timer.php',
               method: "POST",
               data:data,
               success: function(response){             
                    response = JSON.parse(response);  
                    // console.log(response)

                    // for(let i = 0; i< document.querySelectorAll('.pat-status-incoming').length; i++){
                    //     // console.log(document.querySelectorAll('.pat-status-incoming')[i].textContent)
                    //     document.querySelectorAll('.pat-status-incoming')[i].textContent = "asdf"
                    // }

                    for(let i = 0; i < response.length; i++){
                        document.querySelectorAll('.pat-status-incoming')[response[i].table_index].textContent = "On-Process"
                    }

                //    stopwatchDisplay[index].textContent = formatTime(elapsedTime);     
                //    prev_pencil_clicked_index = pencil_index_clicked;          
               }
            })
        }, 1000);

        $('#pendingModal').addClass('hidden')
    }

    // eto yung mag hahandle ng variables before ng reload
    let try_arr = [
        // {index : 0, time: 0 , func : try_timer},
        // {index : 1, time: 0 , func : try_timer},
        // {index : 2, time: 0 , func : try_timer}
    ]

    // eto naman mag hahandle ng variables after ng reload
    let after_reload = []

    for(let j = 0; j < totalRecords; j++){
        try_arr.push({index : j, time: 0 , func : try_timer})
    }

    if($('#timer-running-input').val() === '1'){
        // console.log(try_arr)    
        // try_arr[pencil_index_clicked]['func']('00:02:04');
        // try_arr[pencil_index_clicked]['func'](pencil_index_clicked);

        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){               
                response = JSON.parse(response);
                // response.pop()

                // for(let i = 0; i < response.length; i++){
                //     try_arr[response[i].table_index].time = response[i].elapsedTime
                // }
                // console.log(response)
                after_reload = response
                console.log(after_reload)

                for(let i = 0; i < response.length; i++){
                    try_arr[after_reload[i].table_index]['func'](after_reload[i].table_index , after_reload[i].elapsedTime, after_reload[i].pat_clicked_code);
                }
            }
         })

        console.log(try_arr)    

        

    }
    
    $('#pending-start-btn').on('click' , function(event){
        // clearTimeout(incoming_time)
        // console.log(pencil_index_clicked)
        // console.log(document.querySelectorAll('.hpercode').length)
        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        //     console.log(document.querySelectorAll('.hpercode')[i].value + " , " + i)
        // }
        processing_time_running = true;

        var prev_pencil_clicked_index

        pat_clicked_code = pat_clicked_code_temp
        pencil_index_clicked = pencil_index_clicked_temp
        console.log("pat_clicked_code: " + pat_clicked_code + " - pencil_index_clicked: " + pencil_index_clicked)
        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){     
                response = JSON.parse(response);           
                console.log(response) 
                // console.log(global_hpercode)

                let hpercode_index = 0;
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    if( document.querySelectorAll('.hpercode')[i].value === global_hpercode){
                        hpercode_index = i;
                    }
                }

                // document.querySelectorAll('.pat-status-incoming')[hpercode_index].textContent = "On-Process"

                // document.querySelectorAll('.pat-status-incoming')[global_timer_continue[i]].textContent = "On-Process"
                // stopwatchDisplay[index].textContent = formatTime(elapsedTime);     
                // prev_pencil_clicked_index = pencil_index_clicked;          
            }
         })

        // for(let i = 0; i  < global_timer_continue.length; i++){
        //     document.querySelectorAll('.pat-status-incoming')[global_timer_continue[i]].textContent = "On-Process"
        // }
        // process_timerFunction(processing_time_running, prev_pencil_clicked_index) 
        try_arr[pencil_index_clicked]['func'](pencil_index_clicked , "0" , pat_clicked_code);
        
        // location.reload()

        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        // console.log(document.querySelectorAll('.hpercode')[i].value)
        // }

        // Event listener to start the stopwatch
        // document.getElementById('startButton').addEventListener('click', function() {
        // startTime = new Date().getTime() - elapsedTime;
        // updateStopwatch();
        // timer = setInterval(updateStopwatch, 1000); // Update every second
        // });

        // Event listener to stop the stopwatch
        // document.getElementById('stopButton').addEventListener('click', function() {
        // clearInterval(timer);
        // });

        // // Event listener to reset the stopwatch
        // document.getElementById('resetButton').addEventListener('click', function() {
        // clearInterval(timer);
        // elapsedTime = 0;
        // stopwatchDisplay.textContent = '00:00:00';
        // });
    })
    
    const process_timerFunction = (index) =>{
        // Variables to store time values
        console.log(pencil_index_clicked)
        let startTime = 0; // The timestamp when the stopwatch started
        let elapsedTime = 0; // The total elapsed time in milliseconds
    
        // Reference to the display element
        const stopwatchDisplay = document.querySelectorAll('.stopwatch');

        // Function to format the time in HH:MM:SS format
        function formatTime(milliseconds) {
            const date = new Date(milliseconds);
            return date.toISOString().substr(11, 8);
        }

        // Function to update the stopwatch display
        function updateStopwatch() {

           const stopwatchDisplay = document.querySelectorAll('.stopwatch');

           const currentTime = new Date().getTime();
           elapsedTime = currentTime - startTime;

           const uniqueIdentifier = `interval_${index}`;
           intervalIDs[uniqueIdentifier] = setInterval(() => {
                console.log('here')
               try_arr[index].time += 1;
               stopwatchDisplay[index].textContent = elapsedTime
           }, 1000);
            

           // papano gagana yung isang timer sa madaming functionalities, gl hf tomorrow :)))))
           let data = {
               pat_clicked_code : pat_clicked_code,
               elapsedTime : formatTime(elapsedTime)
           }
           stopwatchDisplay[pencil_index_clicked].textContent = formatTime(elapsedTime);   
           console.log(data)

           // $.ajax({
           //     url: './php/process_timer.php',
           //     method: "POST",
           //     data:data,
           //     success: function(response){               
           //         console.log(response)
           //         stopwatchDisplay[pencil_index_clicked].textContent = formatTime(elapsedTime);     
           //         prev_pencil_clicked_index = pencil_index_clicked;          
           //     }
           // })
        }

       //  if(prev_pencil_clicked_index != pencil_index_clicked){
       //      clearInterval(processing_time);
       //      elapsedTime = 0;
       //      stopwatchDisplay.textContent = '00:00:00';
       //  }

        startTime = new Date().getTime() - elapsedTime;
        updateStopwatch();
        // processing_time = setInterval(updateStopwatch, 1000); // Update every second
        $('#pendingModal').addClass('hidden')
   }

    $('#pending-stop-btn').on('click' , function(event){
        if (intervalIDs.hasOwnProperty('interval_0')) {
            console.log('here')
            clearInterval(intervalIDs['interval_0']);
            delete intervalIDs['interval_0'];
        }
    })

    
})
