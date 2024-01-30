<?php
  session_start();
  include('../database/connection2.php');
  $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE status='Approved' AND approved_time LIKE :proc_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";
  // $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE (status='Approved' OR status='Checked' OR status='Arrived' OR status='Approved') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':proc_date', $formattedDate, PDO::PARAM_STR);
  $stmt->execute();
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($data); echo '</pre>';
  echo $data['COUNT(*)']
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <input type="file" capture="">
</body>
</html>