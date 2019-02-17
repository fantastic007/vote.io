<?php

ob_start();


session_start();
$nid = $_SESSION['nid'];
//passing session data

if($_SESSION['nid'] != $nid)
{
  header('location: /hk_project/index.php');
}
/*
  //all member list
    $ch = curl_init();
      $headers = array(
    'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ch, CURLOPT_URL,'http://10.10.1.98:3000/auth/voters/all');

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADER, 0);
    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);

      $authToken = curl_exec($ch);

      $jsondata = json_decode($authToken,true);
      

      //nominations are stored via API
    if(isset($_POST['nominate'])){

    }
*/

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>vote.io</title>
<meta charset="UTF-8">

  <link rel="stylesheet" type="text/css" href="/am/css/main.css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
   
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
   
  <link rel="stylesheet" href="styles.css" >
   
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</head>
<body>
<center>

<header>

  <h1>vote.io</h1>

</header>



<div class="row">

  <div class="content">

    <h3><?php echo date('Y-m-d'); ?> <br> Elections (Schemes) &nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></h3>
    <br>


      <?php

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, 'http://103.84.159.230:6000/channels?peer=peer0.org1.example.com');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


          $headers = array();
          $headers[] = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1NTAzMjMxMDcsInVzZXJuYW1lIjoic2h1aGFuIiwib3JnTmFtZSI6Ik9yZzEiLCJpYXQiOjE1NTAyODcxMDd9.-es8VySmyKgjleM5t-aOY1i62IDm3_2eNccBtUZDY4M';
          $headers[] = 'Content-Type: application/json';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

          $result = curl_exec($ch);
          if (curl_errno($ch)) {
              echo 'Error:' . curl_error($ch);
          }
          curl_close ($ch);

          $jsonChain = json_decode($result, true);


          foreach ($jsonChain['channels'] as  $value) {
            # code...
           ?>
           <ul>
            <li>
           <a href="nomination.php"> Scheme: Election1 (<?php  echo $value['channel_id']; ?>) </a>
          </li>
         </ul>
           <?php
          }



       ?>


</div>

</div>

</center>

</body>
</html>