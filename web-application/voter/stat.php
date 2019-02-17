
<!DOCTYPE html>
<html lang="en">
<head>
<title>Voting 1.0</title>
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

    <h3><?php echo date('Y-m-d'); ?> <br> Stat &nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></h3>
    <br>

    <!-- <center><p><?php //if(isset($att_msg)) echo $att_msg; if(isset($error_msg)) echo $error_msg; ?></p></center> -->
    
    <form action="" method="post" class="form-horizontal col-md-6 col-md-offset-3">

          <div class="form-group">

      <?php
        //all member list
      $ch = curl_init();
      $headers = array(
    'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ch, CURLOPT_URL,'http://10.10.1.98:3000/voters/nominations?type=1');

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADER, 0);
    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);

      $authToken = curl_exec($ch);

     $jsondata = json_decode($authToken,true);
     curl_close ($ch);


   // reading all voters in the list under the designation
        ?>


    <!-- President Starts-->
              <br><br>
             <label>President</label>
             <br>
        <tr>

    <?php 


    foreach ($jsondata['data']['PM'] as $data) {


//matching with data and query
      $ph = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ph, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$data['id']);

      curl_setopt($ph, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ph, CURLOPT_HEADER, 0);
    
      curl_setopt($ph, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ph, CURLOPT_TIMEOUT, 60);

      $pm = curl_exec($ph);

     $jsondataPM = json_decode($pm,true);
     curl_close ($ph);


       ?>
              
    <td><?php echo $jsondataPM['username']." ".$data['count']; ?><br><td>

     <?php


      } 
           
      ?>

    </tr>

<!--president ends -->


    <!-- v President Starts-->
       <br><br>
             <label>Vice President</label>
             <br>
        <tr>

    <?php 


    foreach ($jsondata['data']['VP'] as $data) {


//matching with data and query
      $ph = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ph, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$data['id']);

      curl_setopt($ph, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ph, CURLOPT_HEADER, 0);
    
      curl_setopt($ph, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ph, CURLOPT_TIMEOUT, 60);

      $pm = curl_exec($ph);

     $jsondataPM = json_decode($pm,true);
     curl_close ($ph);


       ?>
              
    <td><?php echo $jsondataPM['username']." ".$data['count']; ?><br><td>

     <?php


      } 
           
      ?>

    </tr>

<!--v president ends -->




    <!-- gs Starts-->
       <br><br>
             <label>General Secretary</label>
             <br>
        <tr>

    <?php 


    foreach ($jsondata['data']['GS'] as $data) {


//matching with data and query
      $ph = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ph, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$data['id']);

      curl_setopt($ph, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ph, CURLOPT_HEADER, 0);
    
      curl_setopt($ph, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ph, CURLOPT_TIMEOUT, 60);

      $pm = curl_exec($ph);

     $jsondataPM = json_decode($pm,true);
     curl_close ($ph);


       ?>
              
    <td><?php echo $jsondataPM['username']." ".$data['count']; ?><br><td>

     <?php


      } 
           
      ?>

    </tr>

<!--gs ends -->





</div>

    </form>
</div>

  
</div>


</center>

</body>
</html>