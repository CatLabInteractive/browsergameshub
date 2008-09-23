<p class="comment"><?=$contacting?></p>

<?php if (isset ($portalcheck)) { ?>
	<p class="comment"><?=$portalcheck?></p>
<?php } ?>

<?php if (isset ($error)) { ?>
	<?php foreach ($error as $v) { ?>
		<p class="false"><?=$v?></p>
	<?php } ?>
<?php } ?>
<?php if (isset ($success)) { ?>

	<p class="true"><?=$success?></p>
	
	<h2>Spread the word</h2>
	
	<p>Good! Your game now supports the Browser Games Hub API. Encourage developers to use your API by putting the certification logo on your site.</p>
	
	<p>Simply copy &amp; paste this HTML to your website.</p>
	
	<pre class="source"><?=htmlentities('<a href="'.ABSOLUTE_URL.'game/'.$id.'/'.urlencode ($name).'/">
	<img src="'.ABSOLUTE_URL.'images/browsergameshub.png" 
		alt="Browser Games Hub logo" 
		title="'.htmlentities ($name, ENT_COMPAT).' supports the Browser Games Hub API" />
</a>');?></pre>

<!-- Example -->
<div style="margin-top: 10px; text-align: center;">
	<?='<a href="'.ABSOLUTE_URL.'game/'.$id.'/'.urlencode($name).'/">
	<img src="'.ABSOLUTE_URL.'images/browsergameshub.png" 
		alt="Browser Games Hub logo" 
		title="'.htmlentities ($name, ENT_COMPAT).' supports the Browser Games Hub API" />
</a>'?>
</div>

<?php } ?>
