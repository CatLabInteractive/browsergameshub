<p style="float: right;"><a href="<?=$game->getData ('site_url')?>">Visit site</a></p>
<h2><?=$game->getData ('name')?></h2>

<p><?=$game->getDescription ('en')?></p>

<div id="general-information">
	<h3>General Information</h3>
	<table>
		<tr>
			<td class="left">Genre</td>
			<td class="center"><?=$game->getData ('genre', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Setting</td>
			<td class="center"><?=$game->getData ('setting', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Effort</td>
			<td class="center"><?=$game->getData ('effort', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Players</td>
			<td class="center"><?=$game->getData ('players', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Age Recommendation</td>
			<td class="center"><?=$game->getData ('age_recom', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Status</td>
			<td class="center"><?=$game->getData ('status', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Payment</td>
			<td class="center"><?=$game->getData ('payment', '?')?></td>
		</tr>
		
		<tr>
			<td class="left">Timing</td>
			<td class="center"><?=$game->getData ('timing', '?')?></td>
		</tr>
	</table>
</div>

<div id="servers">
	<h3>Game Servers</h3>
	<table>
		<tr>
			<th class="left">Server</th>
			<th class="center">Players</th>
			<th class="center">Status</th>
			<th class="center">Version</th>
		</tr>
		
		<?php $servers = $game->getServers (); ?>
	
		<?php if (count ($servers) > 0) { ?>
			<?php foreach ($servers as $v) { ?>
				<tr>
					<td class="left"><a href="<?=$v['game_url']?>"><?=$v['name']?></a></td>
					<td class="center"><?=$v['players']?></td>
					<td class="center"><?=$v['status']?></td>
					<td class="center"><?=$v['version']?></td>
				</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="center" colspan="4">No information provided.</td>
			</tr>
		<?php } ?>
	</table>
</div>
