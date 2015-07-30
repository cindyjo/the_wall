<?php
session_start();
require_once("new-connection.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>The Wall Main</title>
	<?php
		include('partials/_html_header.php');
	?>
</head>
<body>
	<nav id="main_nav" class="navbar  navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	  				<span class="sr-only">Toggle navigation</span>
	  				<span class="icon-bar"></span>
	  				<span class="icon-bar"></span>
	  				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">The Wall</a>
			</div>

			<div class="navbar-right">
	  			<div id="navbar" class="collapse navbar-collapse">
	        		<ul class="nav navbar-nav">
	        			<li><a href="#">Welcome <?=$_SESSION['logged_user']['first_name']?>!</a></li>
	          			<li><a href="/">Logout</a></li>
	        		</ul>
	  			</div>
	  		</div>
	  	</div>
	</nav>
	<div id ="main_cont" class="container">
		<div class ="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div id="panel" class="panel-heading">
						<h3 class="panel-title">Post a message</h3>
					</div><!-- end of panel-heading -->
					<div class="panel-body">
<?php 					if(isset($_SESSION['post_errors']))
						{
							foreach($_SESSION['post_errors'] AS $error)
							{
?>								<p class="error">*** <?= $error ?></p>	
<?php						}
							unset($_SESSION['post_errors']);
						}
?>	
						<form action="process.php" method="post">
							<input type="hidden" name="action" value="post_message">
						  	<div class="form-group">
						    	<textarea class="form-control" rows="2" name="user_message" placeholder="Write something..."></textarea>
						  	</div>
						 	<button type="submit" class="btn btn-default btn-xs pull-right">Post a message</button>
						</form>
					</div> <!-- end of panel-body -->
				</div> <!-- end of panel -->
			</div> <!-- end of col -->
		</div> <!-- end of row -->
<?php	$message_query = "SELECT users.first_name, users.last_name, messages.message, messages.created_at, messages.id
						FROM messages 
						LEFT JOIN users ON messages.user_id = users.id
						ORDER BY messages.created_at DESC";
		$messages = mysqli_query($connection, $message_query);
		$messages = $messages->fetch_all();
		if(isset($messages))
		{ ?>

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
				<div class ="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<ul class="list-inline">
									<li><span class ="glyphicon glyphicon-comment"></span></li>
									<li><h4><?=$value[0]?> <?=$value[1]?></h4></li>
								</ul>
								<p><small><?= date('F jS Y', strtotime($value[3]))?></small></p>
								<hr>
								<p><?=$value[2]?></p>	
							</div><!-- end of panel-body -->
					
							<div id ="panel-foot" class="panel-footer"> 
<?php							if(isset($comments))
								{
									foreach($comments AS $comment)
									{ ?>
										<ul class="list-inline">
											<li><span class ="glyphicon glyphicon-pencil"></span></li>
											<li><h5><?=$comment[0]?> <?=$comment[1]?></h5></li>
										</ul>
										<div id="comments">
											<p><?= $comment[2]?></p>
											<p><small><?= date('F jS Y', strtotime($comment[3]))?></small></p>
										</div>
<?php								}
								} 	?>				
<?php 							if(isset($_SESSION['comment_errors']))
								{
									foreach($_SESSION['comment_errors'] AS $error)
									{
?>										<p class="error">*** <?= $error ?></p>	
<?php								}
									unset($_SESSION['comment_errors']);
								}
?>	
								<form action="process.php" method="post">
									<input type="hidden" name="action" value="post_comment">
									<input type="hidden" name="message_id" value="<?=$value[4]?>">
									<div class="form-group">
										<textarea class="form-control" rows="1" name = "user_comment" placeholder="Write a comment..."></textarea>
									</div>
									<button class="btn btn-default btn-xs pull-right" type="submit">Post a comment</button>
								</form><br>
							</div><!-- end of panel-footer -->
							
						</div><!-- end of panel -->
					</div><!-- end of col -->
				</div><!-- end of row -->
<?php		}
		} ?><!-- end of isset($messages) -->
	</div> <!-- end of container -->
<?php
		include('partials/_html_footer.php');
?>

</body>
</html>