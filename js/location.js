const locations = (variant) =>{
    let query = ""
    let selected_code
    if(variant === 'region'){
        // console.log("reg")
        selected_code = document.getElementById('sdn-region-select').value
        // console.log('selected code, ' + selected_code)
        query = "region_code"
    }else if(variant === 'province'){
        console.log("pro")
        selected_code = document.getElementById('sdn-province-select').value
        query = "province_code"

    }else if(variant === 'city'){
        selected_code = document.getElementById('sdn-city-select').value
        query = "city_code"
        // console.log(selected_code)
    }

    fetch("php/get_locations.php?" + query + "=" + selected_code + "&" + "val=" + variant)
    .then(response => response.text())
    .then(data =>{
        if(variant === 'region'){
            // console.log(selected_code)
            // console.log(data)
            document.getElementById('sdn-province-select').innerHTML = data;
            // document.getElementById('sdn-region-select').value = "region 1"

        }else if(variant === 'province'){
            // console.log("province_code = ", data)
            document.getElementById('sdn-city-select').innerHTML = data;
        }else if(variant === 'city'){
            // console.log(data)
            // splice the last 4 character of the data to get the zip code
            const zip_code = data.slice(-4);
            // console.log(zip_code)
            document.getElementById('sdn-brgy-select').innerHTML = data;
            document.getElementById('sdn-zip-code').value = zip_code
        }
    })
}

function getLocations(variant){
    locations(variant)
}   