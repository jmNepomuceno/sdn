<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link rel="stylesheet" href="index.css"> -->
</head>
<body>
// SEARCH QUERY RESULT
                const search_query_result = document.querySelector('#search-result-div')
                while (search_query_result.hasChildNodes()) {
                    search_query_result.removeChild(search_query_result.firstChild);
                  }
                // split the string to see if theres a duplicate name
                response = response.split('&')

                for(let i = 0; i < response.length - 1; i++){
                    response[i] = JSON.parse(response[i])

                }
                // console.log(response == "No User Found")
                if(response == "No User Found" || response == ""){
                    const container = document.createElement('h1')
                    container.className = 'mt-2'
                    container.textContent = 'No User Found'
                    document.querySelector('#search-result-div').appendChild(container)
                }else{
                    for(let i = 0; i < response.length - 1; i++){
                        const container = document.createElement('div')
                        container.className = (i % 2 == 0) ? 'w-full h-[80px] flex flex-col justify-center items-center border-b border-black cursor-pointer hover:bg-[#85b2f9]' : 
                        'w-full h-[80px] flex flex-col justify-center items-center border-b border-black cursor-pointer hover:bg-[#85b2f9] bg-[#e6e6e6]'
                        
                        const second = document.createElement('div')
                        second.className = 'w-full h-[40%] flex flex-row justify-between items-center'

                        const h1 = document.createElement('h1')
                        h1.innerHTML = "Patient ID: " + response[i]['patient_code']
                        h1.className = `hover:underline patient-code-${i}`
                        // h1.id = "patient-code-0"

                        const third = document.createElement('div')
                        third.className = 'w-[25%] h-full flex flex-row justify-around items-center'

                        const h1_second = document.createElement('h1')
                        h1_second.innerHTML = response[i]['pat_bdate']

                        const container_second = document.createElement('div')
                        container_second.className = 'w-full h-[40%] flex flex-row justify-start items-center'

                        const h3 = document.createElement('h3')
                        h3.className = `uppercase ml-2 hover:underline patient-name-${i}`
                        h3.innerHTML = response[i]['pat_last_name'] + ", " + response[i]['pat_first_name'] + " " + response[i]['pat_middle_name']
                        // h3.id = "patient-name-" + i

                        container.appendChild(second)
                        container.appendChild(container_second)

                        container_second.appendChild(h3)

                        second.appendChild(h1)
                        second.appendChild(third)

                        third.appendChild(h1_second)

                        document.querySelector('#search-result-div').appendChild(container)
                    }
                }


                // WHEN CLICKED THE NAME OF THE PATIENT
                // hperson-last-name
                //response.length

                // $('.search-result-div').on('click' , function(event){
                //     console.log(event.target.id )
                // })

                for(let i = 0; i < response.length - 1; i++){
                    document.querySelector(".patient-code-" + i).addEventListener('click', function(e){

                        //Personal Information
                        document.querySelector('#hperson-last-name').value = response[i].pat_last_name
                        document.querySelector('#hperson-first-name').value = response[i].pat_first_name
                        document.querySelector('#hperson-middle-name').value = response[i].pat_middle_name
                        // document.querySelector('#hperson-ext-name').value = response[i].pat_last_name

                        //converting of birthdate
                        const timestamp = Date.parse(response[i].pat_bdate);
                        const date = new Date(timestamp)
                        let year = date.getFullYear()
                        let month = (date.getMonth() <= 9 ) ? "0" + (date.getMonth() + 1).toString() : date.getMonth()
                        let day = date.getDate()
                        document.querySelector('#hperson-birthday').value = year.toString() + "-" + month.toString() + "-" + day.toString()

                        //calculating the age based on day of birth

                        const dateOfBirth = year.toString() + "-" + month.toString() + "-" + day.toString()
                        const age = calculateAge(dateOfBirth);
                        document.querySelector('#hperson-age').value = age

                        document.querySelector('#hperson-gender').value = response[0].patsex


                        let cstat = ""
                        switch(response[0].patcstat){
                            case "1": cstat = "Single";break;
                            case "2": cstat = "Married";break;
                            case "3": cstat = "Divorced";break;
                            case "4": cstat = "Widowed";break;
                            default: break;
                        }
                        document.querySelector('#hperson-civil-status').value = cstat
                        

                        // let religion_strings = 
                        document.querySelector('#hperson-religion').value = response[i].relcode
                        
                        document.querySelector('#hperson-occupation').value = (response[i].patempstat) ? response[i].patempstat : "N/A"
                        document.querySelector('#hperson-nationality').value = (response[i].natcode) ? response[i].natcode : "N/A"
                        document.querySelector('#hperson-passport-no').value = (response[i].patempstat) ? response[i].patempstat : "N/A"


                         //Others
                        document.querySelector('#hperson-phic').value = (response[i].phicnum) ? response[i].phicnum : "N/A"
                        // document.querySelector('#hperson-nationality').value = (response[0].natcode) ? response[0].natcode : "N/A"
                    })
                }

                for(let i = 0; i < response.length - 1; i++){
                    document.querySelector(".patient-name-" + i).addEventListener('click', function(e){
                        console.log(response[i])

                    })
                }
</html>