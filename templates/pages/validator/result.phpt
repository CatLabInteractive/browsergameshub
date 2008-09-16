<p class="comment"><?=$contacting?></p>

<?php if (isset ($portalcheck)) { ?>
	<p class="comment"><?=$portalcheck?></p>
<?php } ?>

<?php if (isset ($error)) { ?><p class="false"><?=$error?></p><?php } ?>
<?php if (isset ($success)) { ?><p class="true"><?=$success?></p><?php } ?>
