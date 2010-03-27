<?php 
	ini_set('display_errors',TRUE);
	require_once('_inc/Database.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title> DreamVision - Share your dreams! </title>
<link rel="stylesheet" type="text/css" href="_css/styles.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
	$("#wts").click(function () {
		$("#what").toggle("slow");
	});
	
	$(".comment_link").css('visibility', 'visible');
	$(".comment").hide();
	
	$(".comment_link").click(function (event) {
		$("#"+$(event.target).attr("rel")).toggle("slow");
	});
    $("#what").hide();
  });
</script>
</head>
<body>
<div id="content">
	<?php if (!isset($_POST['input_field'])) : ?>
		<form id="input_form" action="" method="post">
			<h1>DreamVision</h1>
			<label class="form_label" for="input_field">Type in a dream you'd like to share:</label>
			<textarea id="input_field" name="input_field" rows="10" cols="60"></textarea>
			<label class="form_label" for="title_input_field">Title:</label><br/>
			<input type="text" id="title_input_field" name="title_input_field" size="30" maxlength="50"/><br/>
			<input id="submit_button" type="submit" value="Submit your dream!" />
		</form>
	<?php endif; ?>
	<div id="dreams">
	<?php
		if(isset($_POST['input_field']) && !empty($_POST['input_field']))
		{
			if(Database::checkBan() > 0)
			{
				die('Sorry, only one dream/hour may be submitted. Please wait, what about an after-lunch sleep?<br/><a href="/development/DreamVision/">Home</a>');
			}

			Database::insertDream($_POST["title_input_field"],$_POST["input_field"],$_SERVER['REMOTE_ADDR']);
		}
		
		if(isset($_POST['comment_input']) && !empty($_POST['comment_input']))
		{
			if(Database::checkBanComment() > 0)
			{
				die('Sorry, only one comment/5 minutes may be submitted. Please wait, maybe you\'d like to do some power-napping?<br/><a href="/development/DreamVision/">Home</a>');
			}
			
			Database::insertComment($_POST['comment_input'],$_POST['dream_id'],$_SERVER['REMOTE_ADDR']);
		}
		
		$dreams = Database::getDreams();
		
		if($dreams)
		{
			$i = 0;
			
			while ($dream = mysql_fetch_assoc($dreams)) {
				$comments = Database::getComments();
			    ?>
					
				<div id="dream">

				<div class="background_giver">


				<span><em><?=$dream['title']?></em></span>
				<span class="date"><?=$dream['timestamp']?></span>
				<p class="content"><?=$dream['content']?></p>
				<a class="comment_link" rel="comment<?=$i?>" href="#comment<?=$i?>" style="visibility:hidden;">Post Comment</a>
				<form class="comment" id="comment<?=$i?>"action="" method="post">
					<hr/>
					<textarea id="comment_input" name="comment_input" rows="5" cols="40"></textarea><br/>
					<input type="hidden" name="dream_id" value="<?=$dream['id']?>"/>
					<input type="submit" value="submit"/>
				</form>
					<?php while ($comment = mysql_fetch_assoc($comments)) {
						if($comment['dream_id'] == $dream['id'])
						{
						?>
							<div class="comment_block">
								<p><?=$comment['timestamp']?></p>
								<p><?=$comment['content']?></p>
							</div>
						<?php
						}
					}
					?>
				</div>
				</div>
				<?php
				++$i;
			}
		}
		mysql_close(Database::connect());
	?>
	</div>
	<div id="footer">
		&copy; noemig 2010 <a id="wts" href="#what">What the Schnitzel is DreamVision?</a>
	</div>
	<div id="what">
		<p><em>DreamVision</em> is a fun-portal with the background of anonymously sharing the contents of our everyday dreams.</p>
		<p>May they be serious and threatening, funny, ridiculous or just plain beautiful - they are absolutely worth sharing!</p>
	</div>
</div>
</body>