<h2>Browser Games</h2>
<table id="gamelist">

	<tr>
		<th style="width: 20px;">&nbsp;</th>
	
		<th class="left">
			<a href="<?=ABSOLUTE_URL?>list/?sort=name&order=desc" class="sort_za <?=$order=='name_desc'?'active':null?>"><span>Z-A</span></a>
			<a href="<?=ABSOLUTE_URL?>list/?sort=name&order=asc" class="sort_az <?=$order=='name_asc'?'active':null?>"><span>A-Z</span></a>
			<span>Game</span>
		</th>
		<th class="center" style="width: 17%;">
			<a href="<?=ABSOLUTE_URL?>list/?sort=genre&order=desc" class="sort_za <?=$order=='genre_desc'?'active':null?>"><span>Z-A</span></a>
			<a href="<?=ABSOLUTE_URL?>list/?sort=genre&order=asc" class="sort_az <?=$order=='genre_asc'?'active':null?>"><span>A-Z</span></a>
			<span>Genre</span>
		</th>
		<th class="center" style="width: 17%;">
			<a href="<?=ABSOLUTE_URL?>list/?sort=setting&order=desc" class="sort_za <?=$order=='setting_desc'?'active':null?>"><span>Z-A</span></a>
			<a href="<?=ABSOLUTE_URL?>list/?sort=setting&order=asc" class="sort_az <?=$order=='setting_asc'?'active':null?>"><span>A-Z</span></a>
			<span>Setting</span>
		</th>
		<th class="center" style="width: 17%;">
			<a href="<?=ABSOLUTE_URL?>list/?sort=status&order=desc" class="sort_za <?=$order=='status_desc'?'active':null?>"><span>Z-A</span></a>
			<a href="<?=ABSOLUTE_URL?>list/?sort=status&order=asc" class="sort_az <?=$order=='status_asc'?'active':null?>"><span>A-Z</span></a>
			<span>Status</span>
		</th>
		<th class="center" style="width: 17%;">
			<a href="<?=ABSOLUTE_URL?>list/?sort=timing&order=desc" class="sort_za <?=$order=='timing_desc'?'active':null?>"><span>Z-A</span></a>
			<a href="<?=ABSOLUTE_URL?>list/?sort=timing&order=asc" class="sort_az <?=$order=='timing_asc'?'active':null?>"><span>A-Z</span></a>
			<span>Timing</span>
		</th>
	</tr>

	<?php foreach ($games as $game) { ?>
		<tr>
			<td class="center" style="padding: 0px 0px 0px 0px;">
				<?php if ($game['openid']) { ?>
					<img src="<?=ABSOLUTE_URL?>images/lightning.png" title="Direct Play Compatible" />
				<?php } else { ?>&nbsp;<?php } ?>
			</td>
		
			<td class="left"><a href="<?=$game['url']?>"><?=$game['name']?></a></td>
			<td class="center"><?=$game['genre']?></td>
			<td class="center"><?=$game['setting']?></td>
			<td class="center"><?=$game['status']?></td>
			<td class="center"><?=$game['timing']?></td>
		</tr>
	<?php } ?>
</table>

<p class="comment" style="text-align: center;">
	Our list of browser based games is available in <a href="<?=BASE_URL?>xml/">XML</a>. <br />
	All games in this XML have a valid information XML.
</p>
