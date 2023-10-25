<?php
    // session_start();
    // include('../database/connection2.php');
    // include('php/admin_module.php')
    // echo isset($_SESSION["user_name"]);    

    // $sql = "SELECT status FROM incoming_referrals WHERE status='Pending' ORDER BY date_time DESC";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $count = count($data);

    // echo json_encode(['count' => $count]);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>


</head>
<body>
    <h1>Stopwatch</h1>
    <div id="stopwatch">00:00:00</div>
    <button id="startButton">Start</button>
    <button id="stopButton">Stop</button>
    <button id="resetButton">Reset</button>
    


    <script>
        // Variables to store time values
        let startTime = 0; // The timestamp when the stopwatch started
        let elapsedTime = 0; // The total elapsed time in milliseconds
    
        // Reference to the display element
        const stopwatchDisplay = document.getElementById('stopwatch');

        // Function to format the time in HH:MM:SS format
        function formatTime(milliseconds) {
        const date = new Date(milliseconds);
        return date.toISOString().substr(11, 8);
        }

        // Function to update the stopwatch display
        function updateStopwatch() {
        const currentTime = new Date().getTime();
        elapsedTime = currentTime - startTime;
        stopwatchDisplay.textContent = formatTime(elapsedTime);
        }

        // Event listener to start the stopwatch
        document.getElementById('startButton').addEventListener('click', function() {
        startTime = new Date().getTime() - elapsedTime;
        updateStopwatch();
        timer = setInterval(updateStopwatch, 1000); // Update every second
        });

        // Event listener to stop the stopwatch
        document.getElementById('stopButton').addEventListener('click', function() {
        clearInterval(timer);
        });

        // Event listener to reset the stopwatch
        document.getElementById('resetButton').addEventListener('click', function() {
        clearInterval(timer);
        elapsedTime = 0;
        stopwatchDisplay.textContent = '00:00:00';
        });
    </script>
</body>
</html>