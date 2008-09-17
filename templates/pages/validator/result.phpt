<p class="comment"><?=$contacting?></p>

<?php if (isset ($portalcheck)) { ?>
	<p class="comment"><?=$portalcheck?></p>
<?php } ?>

<?php if (isset ($error)) { ?>
	<?php foreach ($error as $v) { ?>
		<p class="false"><?=$v?></p>
	<?php } ?>
<?php } ?>
<?php if (isset ($success)) { ?><p class="true"><?=$success?></p><?php } ?>
