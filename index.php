<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome to the Wall</title>
	<?php
		include('partials/_html_header.php');
	?>
</head>
<body>
<?php
	include('partials/_navbar.php');
?>		
    <div id ="bg" class="jumbotron" >
    	<div id ="jumbo" class="container">
     		<h1>Welcome to the Wall</h1>
    	</div>
    </div>		
	<div id="welcome_cont" class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-1">
<?php 		if(isset($_SESSION['success']))
			{
?>	
				<p class="success"><?=$_SESSION['success']?></p>
			
<?php 			unset($_SESSION['success']);
			}
?>				
			<h4>Already have an account?</h4>

<?php 		if(isset($_SESSION['login_errors']))
			{
				foreach($_SESSION['login_errors'] AS $error)
				{
?>					<p class="error">*** <?= $error ?></p>	
<?php			}
				unset($_SESSION['login_errors']);
			}
?>	
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="login">
				<div class="form-group">
					<label for="email">Email address</label>
					<input type="email" name="email" class="form-control" id="email" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control" id="password" placeholder="Password">
				</div>	
				<button type="submit" class="btn btn-default pull-right">Log in</button>
			</form>
			</div>  <!-- end column -->

			<div class="col-md-4 col-md-offset-2">
				<h4>New to the Wall?</h4>
				<p>Sign up now to vew and post messages on the wall!</p>
<?php 			if(isset($_SESSION['signup_errors']))
				{?>
					<div id="errors">	
<?php				foreach($_SESSION['signup_errors'] AS $error)
					{
?>						<p class="error">*** <?= $error ?></p>	
<?php				}
						unset($_SESSION['signup_errors']);
?>					</div>
<?php 			} ?>	
				<form action="process.php" method="post">
					<input type="hidden" name="action" value="signup">
					<div class="form-group">
						<label for="first_name">First name</label>
						<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First name">
					</div>
					<div class="form-group">
						<label for="last_name">Last name</label>
						<input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last name">
					</div>
					<div class="form-group">
						<label for="email">Email address</label>
						<input type="email" name="email" class="form-control" id="email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password">
					</div>	
					<div class="form-group">
						<label for="passconf">Password</label>
						<input type="password" name="passconf" class="form-control" id="passconf" placeholder="Confirm Password">
					</div>	
					<button type="submit" class="btn btn-default pull-right">Sign up</button>
				</form>
			</div>  <!--end of column-->
		</div>	<!--end of row-->
	</div>  <!--end of container-->
	<?php
		include('partials/_html_footer.php');
	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>