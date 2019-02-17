<?php

ob_start();


session_start();
$nid = $_SESSION['nid'];
//passing session data

if($_SESSION['nid'] != $nid)
{
  header('location: /hk_project/index.php');
}
  //all member list
    if(isset($_POST['vote'])){

     // if(isset($_POST['whichp'])) $position="PM";
      //if(isset($_POST['whichvp'])) $position = "VP";
      //if(isset($_POST['whichgs'])) $position = "GS";

      //post user information to API end /auth/register
      ///voters/cast?from=voter&to=candidate&pos=PM,VP,GS&type=0
  $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://10.10.1.98:3000/voters/cast");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                'from='.$nid.'&to='.$_POST['whichp'].'&pos=PM&type=1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    curl_close ($ch);


      $eh = curl_init();

    curl_setopt($eh, CURLOPT_URL,"http://10.10.1.98:3000/voters/cast");
    curl_setopt($eh, CURLOPT_POST, 1);
    curl_setopt($eh, CURLOPT_POSTFIELDS,
                'from='.$nid.'&to='.$_POST['whichgs'].'&pos=GS&type=1');
    curl_setopt($eh, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


    // receive server response ...
    curl_setopt($eh, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($eh);

    curl_close ($eh);



      $dh = curl_init();

    curl_setopt($dh, CURLOPT_URL,"http://10.10.1.98:3000/voters/cast");
    curl_setopt($dh, CURLOPT_POST, 1);
    curl_setopt($dh, CURLOPT_POSTFIELDS,
                'from='.$nid.'&to='.$_POST['whichvp'].'&pos=VP&type=1');
    curl_setopt($dh, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


    // receive server response ...
    curl_setopt($dh, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($dh);

    curl_close ($dh);



    $jsondata = json_decode($server_output,true);

    //message after registering

        if($jsondata['reply'] == true ){
            $msg = "<br><br><br>Voted Successfully";
          }
          else {
            $ermsg = "Vote cast failed";
          }

    

  
      }


 
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

    <h3><?php echo date('Y-m-d'); ?> <br> Voting Page &nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></h3>
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

      curl_setopt($ch, CURLOPT_URL,'http://10.10.1.98:3000/voters/nominations?type=0');

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
       <tr>
           <td>  <label>President</label>
            <select name="whichp" id="input1">

    <?php 


    foreach ($jsondata['data']['PM'] as $data) {


//matching with data and query
      $ph = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ph, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$data);

      curl_setopt($ph, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ph, CURLOPT_HEADER, 0);
    
      curl_setopt($ph, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ph, CURLOPT_TIMEOUT, 60);

      $pm = curl_exec($ph);

     $jsondataPM = json_decode($pm,true);
     curl_close ($ph);


       ?>
              
    <option  value=<?php echo $jsondataPM['id']; ?> > <?php echo $jsondataPM['username']; ?> </option>

     <?php


      } 
           
      ?>
         </select>

    </td>
    </tr>
<!-- President ends -->



    <!-- Vice President Starts-->
       <tr>
           <td>  <label>Vice President</label>
            <select name="whichvp" id="input1">

    <?php 


    foreach ($jsondata['data']['VP'] as $dataAM) {


//matching with data and query
      $ah = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($ah, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$dataAM);

      curl_setopt($ah, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ah, CURLOPT_HEADER, 0);
    
      curl_setopt($ah, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($ah, CURLOPT_TIMEOUT, 60);

      $am = curl_exec($ah);

     $jsondataAM = json_decode($am,true);
     curl_close ($ah); 


       ?>
              
    <option  value=<?php echo $jsondataAM['id']; ?> > <?php echo $jsondataAM['username']; ?> </option>

     <?php

      }

      

      ?>
         </select>

    </td>
    </tr>
<!-- Vice President ends -->





    <!-- GS Starts-->
       <tr>
           <td>  <label>General Secretary</label>
            <select name="whichgs" id="input1">

    <?php 


    foreach ($jsondata['data']['GS'] as $dataGS) {


//matching with data and query
      $gh = curl_init();
      $headers = array(
        'Content-Type: application/x-www-form-urlencoded'
      );

      curl_setopt($gh, CURLOPT_URL,'http://10.10.1.98:3000/voters/query?nid='.$dataGS);

      curl_setopt($gh, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($gh, CURLOPT_HEADER, 0);
    
      curl_setopt($gh, CURLOPT_RETURNTRANSFER, true);

      // Timeout in seconds
      curl_setopt($gh, CURLOPT_TIMEOUT, 60);

      $gs = curl_exec($gh);

     $jsondataGS = json_decode($gs,true);
     curl_close ($gh);

       ?>
              
    <option  value=<?php echo $jsondataGS['id']; ?> > <?php echo $jsondataGS['username']; ?> </option>

     <?php

      } 

      ?>
         </select>

    </td>
    </tr>
<!-- GS ends -->



</div>

          
     <input type="submit" class="btn btn-primary col-md-2 col-md-offset-5" value="Vote Now" name="vote" />

    </form>
</div>

<a href="stat.php">res</a>
  
  <br> <br> <br> <br> <br>
  <h4><?php 
  if(isset($msg)){
  echo $msg; }

  if(isset($ermsg)  ) echo $ermsg;
   ?></h4>




   <?php

         //time API call

      $th = curl_init();

      curl_setopt($th, CURLOPT_URL, 'http://103.84.159.230:6000/channels/mychannel/chaincodes/mycc?peer=peer0.org1.example.com&fcn=whichTime&args=%5B%22%22%5D');
      curl_setopt($th, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($th, CURLOPT_CUSTOMREQUEST, 'GET');


      $headers = array();
      $headers[] = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1NTAzMjMxMDcsInVzZXJuYW1lIjoic2h1aGFuIiwib3JnTmFtZSI6Ik9yZzEiLCJpYXQiOjE1NTAyODcxMDd9.-es8VySmyKgjleM5t-aOY1i62IDm3_2eNccBtUZDY4M';
      $headers[] = 'Content-Type: application/json';
      curl_setopt($th, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($th);
      if (curl_errno($th)) {
          echo 'Error:' . curl_error($th);
      }
      curl_close ($th);

      //$jsonTime = json_decode($result, true);

     //echo $result;


//comment out API block
  
      if($result == "=>finished"){
        header('location: stat.php');
      }

      else if($result == "=>before"){
        header('location: index.php');
      }






    ?>
  
</div>


</center>

</body>
</html>