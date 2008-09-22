<h2>Login</h2>

<p class="false">To use our Direct Play feature, you must login with a valid OpenID account. <br />Your OpenID account will be used to login at Direct Play browser games.</p>

<form method="get" action="<?=ABSOLUTE_URL?>openid/">
	<fieldset>
		<legend>OpenID Login</legend>
		
		<label>Your OpenID Account:</label>
		
		<div id="idselector_button">
			<input type="text" name="openid_url" id="openidselector" />
		</div>
		
		<button type="submit" style="clear: both; margin-top: 5px;" id="login_button">Login</button>
	</fieldset>
</form>

<?=$about?>

<!-- BEGIN ID SELECTOR -->
<script type="text/javascript" id="openidselector" src="https://www.idselector.com/selector/1ea71efb3f49bfc9432f0bd848bc8ddd3fe77b9a" charset="utf-8"></script>
<!-- END ID SELECTOR -->
