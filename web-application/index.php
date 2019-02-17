	<?php

	ob_start();


		if(isset($_POST['login'])){


			$nid = $_POST['nid'];
			$password = $_POST['password'];

		//session starting
		session_start();
		$_SESSION['nid'] = $nid;

		//get for user,pass and response from url end /auth/login

		$ch = curl_init();
	    $headers = array(
	 	'Content-Type: application/x-www-form-urlencoded'
	    );

	    curl_setopt($ch, CURLOPT_URL,'http://10.10.1.98:3000/auth/login?nid='.$nid.'&password='.$password);

	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    // Timeout in seconds
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	    $authToken = curl_exec($ch);

			$jsondata = json_decode($authToken,true);
			

			//redirecting to login page or nomination page
				if($jsondata['reply'] == true ){
						header('location: voter/index.php');
					}
					else {
						$msg = $jsondata['message'];
						//header('location: index.php');
					}


		}



	?>



<!DOCTYPE html>
<html lang="en">
<head>

	<title>vote.io</title>
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
	<center>

<header>

  <h1>vote.io</h1>

</header>

<h1>Login</h1>


<div class="content">
	<div class="row">

	<form method="post" class="form-horizontal col-md-6 col-md-offset-3" >
			<div class="form-group">
			    <label for="input1" class="col-sm-3 control-label">NID</label>
			    <div class="col-sm-7">
			      <input type="text" name="nid"  class="form-control" id="input1" placeholder="your NID" required="required" maxlength="6" minlength="4" />
			    </div>
			</div>

			<div class="form-group">
			    <label for="input1" class="col-sm-3 control-label">Password</label>
			    <div class="col-sm-7">
			      <input type="password" name="password"  class="form-control" id="input1" placeholder="your password" required="required" />
			    </div>
			</div>



			<input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Login" name="login" />
		</form>
	</div>
	<br>

	<h4><?php
		if(isset($msg)) echo $msg;

	 ?></h4>
</div>



<br><br>

<p><strong>If you don't have any account, <a href="signup.php">Signup</a> here</strong></p>

</center>
</body>
</html>



















