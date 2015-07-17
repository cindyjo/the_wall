<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<title>CodingDojo Wall Main</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id = "wrapper">
		<div id="header">
			<h2>CodingDojo Wall</h2>
			<p>Welcome</p>
		</div>
<?php 	if(isset($_SESSION['success']))
		{
?>			
		<p class="success"><?=$_SESSION['success']?></p>
<?php 		unset($_SESSION['success']);
		}
?>	
		<div id = "login">
			<h4>Already have an account?</h4>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="login">
				<input type="text" name="email" placeholder="Email">
				<input type="password" name="password" placeholder ="Password">
				<input id="login_submit" type="submit" value="Log in">
			</form>
		</div>  <!-- end of login -->
<?php 	if(isset($_SESSION['login_errors']))
		{?>
			<div id="errors">	
<?php			foreach($_SESSION['login_errors'] AS $error)
				{
?>					<p class="error">*** <?= $error ?></p>	
<?php			}
					unset($_SESSION['login_errors']);
?>			</div>
<?php 	} ?>			

		<div id="to_signup">
			<h4>New to CodingDojo Wall?</h4>
			<p>Sign up now to vew and post messages on the wall!</p>
			<a href="./signup.php"><button type="button">Sign up</button></a>
		</div><!-- end of signup-->
	</div> <!-- end of wrapper -->
</body>
</html>