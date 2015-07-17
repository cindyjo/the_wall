<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<title>CodingDojo Wall Sign-up</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id = "wrapper">
		<div id="header">
			<h2>CodingDojo Wall</h2>
			<p>Welcome</p>
		</div>
		<div id = "signup_page">
			<h2>Join CodingDojo Wall.</h2>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="signup">
				<input type="text" name="first_name" placeholder = "First Name">
				<input type="text" name="last_name" placeholder = "Last Name">
				<input type="text" name="email" placeholder = "Email">
				<input type="password" name="password" placeholder ="Password">
				<input type="password" name="passconf" placeholder ="Confirm Password">
				<input id="signup_submit" type="submit" value="Sign up">
			</form>
		</div> <!-- end of signup_page-->
<?php 	if(isset($_SESSION['signup_errors']))
		{?>
			<div id="errors">	
<?php			foreach($_SESSION['signup_errors'] AS $error)
				{
?>					<p class="error">*** <?= $error ?></p>	
<?php			}
					unset($_SESSION['signup_errors']);
?>			</div>
<?php 	} ?>	
	</div> <!-- end of wrapper -->
</body>
</html>