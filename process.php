<?php
session_start();
require_once("new-connection.php");
global $connection;
date_default_timezone_set('America/Los_Angeles');

$signup_errors=array();
$login_errors=array();

// a helper function to check if the input text contains numbers
function num_check($name) 
{
	for($i =0; $i<strlen($name); $i++)
	{
		if(is_numeric($name[$i]))
		{
			return true;
		}
	}
	return false;
}
// sign-up validation
// 1. first name and last name cannot be empty and cannot conain numbers
// 2. email cannot be empty, must be in valid form, and unique
// 3. password and password confirmation cannot be empty
// 4. password and password confirmation must match
// if sign-up validation fails, error messages will render on the view page-index.html
// if sign-up success, a success message will render and ask th user to login  

if(isset($_POST['action']) && $_POST['action'] == "signup")
{
	if(num_check($_POST['first_name']) || strlen($_POST['first_name']) <1)
	{
		$signup_errors[]="First name is required / cannot contain numbers.";
	}
	if(num_check($_POST['last_name'])|| strlen($_POST['last_name']) <1)
	{
		$signup_errors[]="Last name is required / cannot contain numbers.";
	}
	if(strlen($_POST['email']) < 1)
	{
		$signup_errors[] = "Email address required.";
	}
	else 
	{
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$signup_errors[] = "Invalid email address.";
		}
		// query to fetch an existing email from database
		$esc_email = mysqli_real_escape_string($connection, $_POST['email']);
		$esc_email = strtolower($esc_email);

		$checkForEmail = "SELECT * FROM users WHERE email = '{$esc_email}'";

		$user = fetch($checkForEmail);

		if(count($user) > 0)
		{
			$signup_errors[] = "An account for that email address already exists.";
		}
	}
	if(strlen($_POST['password']) < 1)
	{
		$signup_errors[] = "Password required.";
	}
	else
	{
		if($_POST['password'] != $_POST['passconf'])
		{
			$signup_errors[] = "Password must match password confirmation field.";
		}
	}

	if(strlen($_POST['passconf']) < 1)
	{
		$signup_errors[] = "Password confirmation required.";
	}

	if(count($signup_errors) > 0)
	{
		$_SESSION['signup_errors'] = $signup_errors;
		header("Location: index.php");
		exit();
	}
	else 
	{
		//queries to enter user data into database
		$esc_first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$esc_last_name = mysqli_real_escape_string($connection, $_POST['last_name']);

		$salt = bin2hex(openssl_random_pseudo_bytes(22));
		$encrypted_password = md5($_POST['password'] . '' . $salt);

		$query = "INSERT INTO users (first_name, last_name, email, password, salt, created_at, updated_at)
					VALUES ('{$esc_first_name}', '{$esc_last_name}', '{$esc_email}', '{$encrypted_password}', '{$salt}', NOW(), NOW())";
		
		if(run_mysql_query($query))
		{
			$_SESSION['success'] = "Successfully Joined! Please login to continue.";
			header("Location: index.php");
			exit();
		}
		else
		{
			$_SESSION['signup_errors'] = array("Adding user to the DB failed for some reason");
			header("Location: index.php");
			exit();
		}
	}
}

// login validation
// 1. email and password cannot be empty


if(isset($_POST['action']) && $_POST['action'] == "login")
{
	if(strlen($_POST['email']) < 1)
	{
		$login_errors[] = "Email address required.";
	}	

	if(strlen($_POST['password']) < 1)
	{
		$login_errors[] = "Password required.";
	}

	if(count($login_errors) > 0)
	{
		$_SESSION['login_errors'] = $login_errors;
		header("Location: index.php");
		exit();
	}
	else 
	{
		// queries to fetch an existing account based on email
		$esc_email = mysqli_real_escape_string($connection, $_POST['email']);
		$esc_email = strtolower($esc_email);

		$query = "SELECT * FROM users WHERE email = '{$esc_email}'";
		$user = fetch($query);
		// checking for the correct password for the fetched user 
		if(!empty($user))
		{
			$encrypted_password = md5($_POST['password'] . '' . $user['salt']);
			if($encrypted_password != $user['password'])
			{
				$_SESSION['login_errors'] = array("BAD login credentials.");
				header("Location: index.php");
				exit();
			}
			else if($encrypted_password == $user['password'])
			{
				$_SESSION['logged_user'] = array("id" => $user['id'], "first_name" => $user['first_name'], 
												"last_name" =>$user['last_name']);
				header("location: main.php");
				exit();
			}
			else 
			{
				die("Something else happend");
			}
		}
		else
		{
			$_SESSION['login_errors'] = array("Bad login credentials.");
			header("Location: index.php");
			exit();
		}
	}
}

// processing post messages
// validation-message cannot be empty
$post_errors=array();
if(isset($_POST['action']) && $_POST['action'] == "post_message")
{
	if(strlen($_POST['user_message']) <1){
		$post_errors[] = "Your message cannot be empty!";
	}

	if(count($post_errors) > 0)
	{
		$_SESSION['post_errors'] = $post_errors;
		header("Location: main.php");
		exit();
	}
	else 
	{
		//query to insert message into database
		$esc_user_message = mysqli_real_escape_string($connection, $_POST['user_message']);

		$query = "INSERT INTO messages (message, created_at, updated_at, user_id)
					VALUES ('{$esc_user_message}', NOW(), NOW(), '{$_SESSION["logged_user"]["id"]}')";
		
		run_mysql_query($query);
		header('Location: main.php');
	}
}

// processing post comments
// validation-comment cannot be empty
 
$comment_errors=array();
if(isset($_POST['action']) && $_POST['action'] == "post_comment")
{

	if(strlen($_POST['user_comment']) <1){
		$comment_errors[] = "Your commment cannot be empty!";
	}

	if(count($comment_errors) > 0)
	{
		$_SESSION['comment_errors'] = $comment_errors;
		header("Location: main.php");
		exit();
	}
	else 
	{
		//queries to insert comments into database
		$esc_user_comment = mysqli_real_escape_string($connection, $_POST['user_comment']);

		$query = "INSERT INTO comments (comment, created_at, updated_at, user_id, message_id)
					VALUES ('{$esc_user_comment}', NOW(), NOW(), '{$_SESSION["logged_user"]["id"]}', '{$_POST["message_id"]}')";
		
		run_mysql_query($query);
		header("Location: main.php");
	}
}















?>