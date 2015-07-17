<?php
session_start();
require_once("new-connection.php");
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
			<p>Welcome <?=$_SESSION['logged_user']['first_name']?></p>
		</div>
		<div id = "post">
			<h4>Post a message</h4>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="post_message">
				<textarea name="user_message"></textarea>
				<input id="post_submit" type="submit" value="Post a message">
			</form>
		</div>  <!-- end of post-->
<?php	$message_query = "SELECT users.first_name, users.last_name, messages.message, messages.created_at, messages.id
						FROM users 
						LEFT JOIN messages ON messages.user_id = users.id
						ORDER BY messages.created_at DESC";
		$messages = mysqli_query($connection, $message_query);
		$messages = $messages->fetch_all();

		if(isset($messages))
		{ ?>
		<div id="print_messages">
<?php 		foreach($messages AS $value)
			{ 
				$comment_query = "SELECT users.first_name, users.last_name, comments.comment, comments.created_at, messages.id
								FROM comments
								LEFT JOIN messages on comments.message_id = messages.id
								LEFT JOIN users on comments.user_id = users.id
								WHERE comments.message_id = '{$value[4]}'
								ORDER BY comments.created_at ASC";

				$comments = mysqli_query($connection, $comment_query);
				$comments = $comments->fetch_all();		
?>
				<h4><?=$value[0]?> <?=$value[1]?> <?= date('F jS Y', strtotime($value[3]))?></h4>
				<p><?=$value[2]?></p>		
				<div id="comments">
<?php				if(isset($comments))
					{
						foreach($comments AS $comment)
						{ ?>
							<h5><?=$comment[0]?> <?=$comment[1]?> <?= date('F jS Y', strtotime($comment[3]))?></h5>
							<p class="comment"><?= $comment[2]?></p>
<?php					}
					} 	?>				
					<p id = "comment_title">Post a comment</p>
					<form action="process.php" method="post">
						<input type="hidden" name="action" value="post_comment">
						<input type="hidden" name="message_id" value="<?=$value[4]?>">
						<textarea name = "user_comment"></textarea>
						<input id="comment_submit" type="submit" value="Post a comment">
					</form>
				</div>
<?php		} ?>			
		</div>
<?php	} ?>
	</div> <!-- end of wrapper -->
</body>
</html>