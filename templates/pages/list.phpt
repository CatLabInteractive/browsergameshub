<h2>Browser Games</h2>

<p class="comment" style="text-align: center; font-size: 10px;">
	Our list of browser based games is available in <a href="<?=BASE_URL?>xml/">XML</a> aswell.
</p>

<form method="get" action="<?=ABSOLUTE_URL?>list/">
	<table id="gamelist">

		<tr>
			<th style="width: 20px;">&nbsp;</th>
	
			<th class="left">
				<a href="<?=$order_url?>sort=name&order=desc" class="sort_za <?=$order=='name_desc'?'active':null?>"><span>Z-A</span></a>
				<a href="<?=$order_url?>sort=name&order=asc" class="sort_az <?=$order=='name_asc'?'active':null?>"><span>A-Z</span></a>
				<span>Game</span>
			</th>
			<th class="center" style="width: 17%;">
				<a href="<?=$order_url?>sort=genre&order=desc" class="sort_za <?=$order=='genre_desc'?'active':null?>"><span>Z-A</span></a>
				<a href="<?=$order_url?>sort=genre&order=asc" class="sort_az <?=$order=='genre_asc'?'active':null?>"><span>A-Z</span></a>
				<span>Genre</span>
			</th>
			<th class="center" style="width: 17%;">
				<a href="<?=$order_url?>sort=setting&order=desc" class="sort_za <?=$order=='setting_desc'?'active':null?>"><span>Z-A</span></a>
				<a href="<?=$order_url?>sort=setting&order=asc" class="sort_az <?=$order=='setting_asc'?'active':null?>"><span>A-Z</span></a>
				<span>Setting</span>
			</th>
			<th class="center" style="width: 17%;">
				<a href="<?=$order_url?>sort=status&order=desc" class="sort_za <?=$order=='status_desc'?'active':null?>"><span>Z-A</span></a>
				<a href="<?=$order_url?>sort=status&order=asc" class="sort_az <?=$order=='status_asc'?'active':null?>"><span>A-Z</span></a>
				<span>Status</span>
			</th>
			<th class="center" style="width: 17%;">
				<a href="<?=$order_url?>sort=timing&order=desc" class="sort_za <?=$order=='timing_desc'?'active':null?>"><span>Z-A</span></a>
				<a href="<?=$order_url?>sort=timing&order=asc" class="sort_az <?=$order=='timing_asc'?'active':null?>"><span>A-Z</span></a>
				<span>Timing</span>
			</th>
		</tr>
	
		<tr>
			<td>&nbsp;</td>
			<td><input class="filter name" type="text" name="name" style="text-align: left;" value="<?=$filters['name']?>" /></td>
			<td class="center"><input class="filter name" type="text" name="genre" value="<?=$filters['genre']?>" /></td>
			<td class="center"><input class="filter name" type="text" name="setting" value="<?=$filters['setting']?>" /></td>
			<td class="center"><input class="filter name" type="text"  name="status" value="<?=$filters['status']?>" /></td>
			<td class="center"><input class="filter name" type="text" name="timing" value="<?=$filters['timing']?>" /></td>
		</tr>

		<?php foreach ($games as $game) { ?>
			<tr>
				<td class="center" style="padding: 0px 0px 0px 0px;">
					<?php if ($game['openid']) { ?>
						<img src="<?=ABSOLUTE_URL?>images/lightning.png" title="Direct Play Compatible" />
					<?php } else { ?>&nbsp;<?php } ?>
				</td>
		
				<td class="left"><a href="<?=$game['url']?>"><?=$game['name']?></a></td>
				<td class="center"><a class="filter" href="<?=ABSOLUTE_URL?>list/?genre=<?=urlencode ($game['genre'])?>"><?=$game['genre']?></a></td>
				<td class="center"><a class="filter" href="<?=ABSOLUTE_URL?>list/?setting=<?=urlencode ($game['setting'])?>"><?=$game['setting']?></a></td>
				<td class="center"><a class="filter" href="<?=ABSOLUTE_URL?>list/?status=<?=urlencode ($game['status'])?>"><?=$game['status']?></a></td>
				<td class="center"><a class="filter" href="<?=ABSOLUTE_URL?>list/?timing=<?=urlencode ($game['timing'])?>"><?=$game['timing']?></a></td>
			</tr>
		<?php } ?>
	</table>
	
	<p style="text-align: right; margin-top: 0px; ">
		<button type="submit" style="display: inline;" style="margin-right: 2px;">Apply filters</button>
		<button type="button" style="display: inline;" onclick="window.location = '<?=ABSOLUTE_URL?>list?'; return false;">Clear filters</button>
	</p>
	
</form>
