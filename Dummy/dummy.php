<?php
  session_start();
  include '../database/connection.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <!-- <form action="Dummy/dummy.php" method="post"> -->
    <form action="dummy.php" method="post">

        What's your name <br>
        <input type="text" name="first_name" placeholder="First Name" required> <br>
        <input type="text" name="last_name" placeholder="Last Name" required> <br>
        <input type="text" name="middle_name" placeholder="Middle Name" required> <br>
        <input type="date" id="selected_date" name="selected_date" placeholder="Date" required> <br>
        <input type="text" name="mobileno" placeholder="Mobile Number" required> <br>
        <input type="text" name="user_name" placeholder="Username" required> <br>
        <input type="password" name="password" placeholder="Password"   required  > <br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br>


 
        <input type="submit" name="login" value="Submit"><br>


        

        
        <a href="Records.php">    Records    </a>
        



    </form>





</body>
</html>



<?php



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password === $confirmPassword) {
        // Passwords match, proceed with registration or processing.
        // You can hash and store the password securely in your database.

        if(isset($_POST['login']))
        {
            try {
        
                // connect to mysql
        
                $pdoConnect = new PDO("mysql:host=localhost;dbname=sdndb","root","S3rv3r");
            } catch (PDOException $exc) {
                echo $exc->getMessage();
                exit();
            }
        
        
            $firstname = $_POST['first_name'];
            $lastname = $_POST['last_name'];
            $middlename = $_POST['middle_name'];
            $birthday = $_POST['selected_date'];
            $mobileno = $_POST['mobileno'];
            $username = $_POST['user_name'];    
            $passwords = $_POST['password'];
            $confirmpassword = $_POST['confirm_password'];
           
           
           
           
            // // Make the months array:
           
            // $months = array ('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            // $months = array ('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            // $month_num = range(1, 12);
            // $months = array_combine($month_num, $months);
           
            
            // // mysql query to insert data
        
            $pdoQuery = "INSERT INTO user (first_name, last_name, middle_name, birthday, mobileno, user_name, password)
                     VALUES (:first_name, :last_name, :middle_name, :selected_date, :mobileno, :user_name, :password)";
            
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            
            $pdoExec = $pdoResult->execute(array(
                ":first_name" => $firstname,
                ":last_name" => $lastname,
                ":middle_name" => $middlename,
                ":selected_date" => $birthday,
                ":mobileno" => $mobileno,
                ":user_name" => $username,
                ":password" => $passwords,
            ));
            
                // check if mysql insert query successful
            if($pdoExec)
            {
                echo 'Data Inserted';
            }
            
            
            
            else{
                echo 'Data Not Inserted';
            }
        }
        
        




    } else {
        // Passwords do not match, display an error message.
        echo "Passwords do not match. Please try again.";
    }
}









?>
