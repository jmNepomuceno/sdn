<?php 
    include("../database/connection2.php");

    //$stmt = $pdo->query('SELECT region_code, region_description from region');
    // $sql = 'SELECT region_code, region_description FROM region';
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo count($data);                                   
    
    // foreach($data as $value){
    //     echo $data['region_description'];
    // }
    // while($data){
    //     echo $data['id'];
    // }      
    // echo '<pre>'; print_r($data); echo '</pre>';
    // echo $data[0]['id'];
    // echo count($data[0]);        
    
    // $stmt = $pdo->query('SELECT region_code, region_description from region');
    // // $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
    //     echo '<pre>'; print_r($data); echo '</pre>';
    // }
        //echo '<pre>'; print_r($data); echo '</pre>';
        // $stmt = $pdo->query('SELECT region_code, region_description from region');
        // while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
        //     echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
        // }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <select id='sample-select' name="cars" id="cars">
        <option value="volvo">Volvo</option>
        <option value="saab" selected>Saab</option>
        <option value="mercedes">Mercedes</option>
        <option value="audi">Audi</option>
    </select>


    <script type="text/javascript">
        const sample_select = document.querySelector('#sample-select')
        console.log(sample_select)
        // sample_select.addEventListener('change', function(){
        //     console.log(sample_select.innerHtml)
        // })

    </script>
</body> 
</html>