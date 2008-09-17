<h2>Browser Games</h2>
<p class="comment">
	The list of browser games will be released soon. We are still finalising our output API's. Lists will be available in XML, JSON and HTML.
</p>
<table>

	<tr>
		<th class="left">Game</th>
		<th class="center">Genre</th>
	</tr>

	<?php foreach ($games as $game) { ?>
		<tr>
			<td class="left"><a href="<?=$game['url']?>"><?=$game['name']?></a></td>
			<td class="center"><?=$game['genre']?></td>
		</tr>
	<?php } ?>
</table>
