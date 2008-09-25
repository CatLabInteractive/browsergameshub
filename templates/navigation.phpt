<h2>Browser Based Games</h2>
<ul>
	<li><a href="<?=BASE_URL?>index">Home</a></li>
	<li><a href="<?=BASE_URL?>howto">Add Game</a></li>
	<!--<li><a href="wiki">Wiki</a></li>-->
	<li><a href="<?=BASE_URL?>list">Games List</a></li>
</ul>

<h2 style="margin-top: 10px;">Community</h2>
<ul>
	<li><a href="<?=BASE_URL?>forum/" target="_BLANK">Forum</a></li>
	<li><a href="<?=BASE_URL?>login">Login</a></li>
</ul>

<?php
	$linkus = '<a href="'.ABSOLUTE_URL.'"><img src="'.ABSOLUTE_URL.'images/browsergameshub.png" alt="'.ABSOLUTE_URL.'" title="Browser Games Hub" /></a>';
?>

<h2>Link Us!</h2>
<p style="text-align: center;">
	<textarea style="width: 160px; height: 50px; font-size: 10px; overflow: hidden;" onclick="javascript:this.focus();this.select();"><?php echo htmlentities ($linkus); ?></textarea>
</p>

<p style="text-align: center;">
	<?=$linkus?>
</p>
