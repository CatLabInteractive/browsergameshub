<h2>Browser Games</h2>
<p class="comment" style="text-align: center;">
	Our list of browser based games is available in <a href="<?=BASE_URL?>xml/">XML</a>. <br />
	All games in this XML have a valid information XML.
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
