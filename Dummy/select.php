<?php
    // select a particular user by id
    // $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    // $stmt->execute(['id' => $id]); 
    // $user = $stmt->fetch();

//     $(document).on('click','#showData',function(e){
//         $.ajax({    
//           type: "GET",
//           url: "backend-script.php",             
//           dataType: "html",                  
//           success: function(data){                    
//               $("#table-container").html(data); 
             
//           }
//       });
//   });


    include("../database/connection2.php");

    if($_POST){
        include("../database/connection2.php");
        $username = $_POST['username'];
        $password = $_POST['password'];  
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // echo $hash;
        $query = 'SELECT * FROM telemedicine WHERE username="'. $username .'" ';

        $stmt = $pdo->prepare($query);    
        $stmt->execute();
        $data = $stmt->fetchAll();
        
        //echo '<pre>'; print_r($data); echo '</pre>';
        // echo $data[0]['password'];

        if (password_verify($password, $data[0]['password'])) {
            echo 'Password is valid!';
        } else {
            echo 'Invalid password.';
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    
    <form action="select.php" method="post">
      <input type="text" name="username" placeholder="Username"><br>
      <input type="password" name="password" placeholder="Password"><br>

      <input type="submit" name="submit" value="login">
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('input[name="username"]').keyup(function(){
                console.log("here")
            })
        })
        
    </script>
</body>
</html>