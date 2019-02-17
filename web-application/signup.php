  <?php

    if(isset($_POST['signup'])){

      //post user information to API end /auth/register

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://10.10.1.98:3000/auth/register");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "nid=".$_POST['nid']."&password=".$_POST['password']."&username=".$_POST['username']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    curl_close ($ch);

    $jsondata = json_decode($server_output,true);

    //message after registering

        if($jsondata['reply'] == true ){
            $msg = "Registration Successful";
          }
          else {
            $ermsg = "Registration failed";
          }
      

  
      }


  ?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>vote.io</title>
<meta charset="UTF-8">
  
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
   
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
   
  <link rel="stylesheet" href="styles.css" >
   
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<header>

  <h1>vote.io</h1>

</header>
<center>
<h1>Signup</h1>
<div class="content">

  <div class="row">

    <form method="post" class="form-horizontal col-md-6 col-md-offset-3">

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">NID</label>
          <div class="col-sm-7">
            <input type="text" name="nid"  class="form-control" id="input1" placeholder="national ID number" required="required"/>
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Name</label>
          <div class="col-sm-7">
            <input type="text" name="username"  class="form-control" id="input1" placeholder="your name" required="required"/>
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Password</label>
          <div class="col-sm-7">
            <input type="password" name="password"  class="form-control" id="input1" placeholder="choose a strong password" required="required"/>
          </div>
      </div>


      <input type="submit" class="btn btn-primary col-md-2 col-md-offset-8" value="Signup" name="signup" />
    </form>
  </div>
<br> <br> <br> <br> <br>
  <h4><?php 
  if(isset($msg)){
  echo $msg; }

  if(isset($ermsg)  ) echo $ermsg;
   ?></h4>
  
    <br>
    <p><strong>Already have an account? <a href="index.php">Login</a> here.</strong></p>

</div>

</center>

</body>
</html>
