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
        var timeString = "00:00:28"; // Example time string in "hh:mm:ss" format
        var match = timeString.match(/(\d+):(\d+):(\d+)/);

        if (match) {
        var hours = parseInt(match[1], 10);
        var minutes = parseInt(match[2], 10);
        var seconds = parseInt(match[3], 10);

        var totalMinutes = hours * 60 + minutes + seconds / 60;
        console.log(totalMinutes); // Output: 3.466666666666667
        } else {
        console.log("No time components found in the string");
        }
    </script>
</body>
</html>